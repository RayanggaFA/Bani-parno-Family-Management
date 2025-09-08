<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Member;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $totalFamilies = Family::count();
        $totalMembers = Member::count();
        $recentMembers = Member::with('family')
            ->latest()
            ->limit(6)
            ->get();
        
        return view('public.home', compact('totalFamilies', 'totalMembers', 'recentMembers'));
    }

    public function families(Request $request)
    {
        $query = Family::withCount('members');
        
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('domicile', 'like', '%' . $request->search . '%');
        }
        
        $families = $query->orderBy('name')->paginate(12);
        
        return view('public.families', compact('families'));
    }

    public function family(Family $family)
    {
        $family->load(['members' => function($query) {
            $query->orderBy('generation')->orderBy('full_name');
        }]);
        
        // Group members by generation
        $membersByGeneration = $family->members->groupBy('generation');
        
        // Statistics
        $stats = [
            'total' => $family->members->count(),
            'male' => $family->members->where('gender', 'Laki-laki')->count(),
            'female' => $family->members->where('gender', 'Perempuan')->count(),
            'married' => $family->members->where('status', 'Sudah Menikah')->count(),
        ];
        
        return view('public.family-detail', compact('family', 'membersByGeneration', 'stats'));
    }

    public function members(Request $request)
{
    $query = Member::with('family');
    
    // Filters
    if ($request->filled('family_id')) {
        $query->where('family_id', $request->family_id);
    }
    
    if ($request->filled('gender')) {
        $query->where('gender', $request->gender);
    }
    
    if ($request->filled('generation')) {
        $query->where('generation', $request->generation);
    }
    
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    if ($request->filled('city')) {
        $query->where('domicile_city', 'like', '%' . $request->city . '%');
    }
    
    if ($request->filled('search')) {
        $query->where(function($q) use ($request) {
            $q->where('full_name', 'like', '%' . $request->search . '%')
              ->orWhere('occupation', 'like', '%' . $request->search . '%')
              ->orWhere('birth_place', 'like', '%' . $request->search . '%');
        });
    }

    // Sorting
    $sortBy = $request->get('sort', 'full_name');
    $sortDirection = $request->get('direction', 'asc');
    $query->orderBy($sortBy, $sortDirection);

    $members = $query->paginate(20)->appends($request->all());
    
    // Data untuk filters
    $families = Family::orderBy('name')->pluck('name', 'id');
    $cities = Member::select('domicile_city')->distinct()->orderBy('domicile_city')->pluck('domicile_city');
    
    // Return table view instead of card view
    return view('public.members', compact('members', 'families', 'cities'));
}


    public function member(Member $member)
    {
        $member->load(['family', 'parent', 'children']);
        
        // Find siblings (same parent or no parent but same generation)
        $siblings = collect();
        if ($member->parent_id) {
            $siblings = Member::where('parent_id', $member->parent_id)
                            ->where('id', '!=', $member->id)
                            ->get();
        }
        
        return view('public.member-detail', compact('member', 'siblings'));
    }

    public function show($id)
{
    $family = Family::with('members')->findOrFail($id);
    return view('public.family-detail', compact('family'));
}


    public function familyTree(Family $family)
    {
        // Get family tree structure
        $generations = Member::where('family_id', $family->id)
                            ->orderBy('generation')
                            ->orderBy('full_name')
                            ->get()
                            ->groupBy('generation');
        
        return view('public.family-tree', compact('family', 'generations'));
    }

        public function activityHistory(Request $request)
    {
        $query = ActivityLog::query();

        // Filter berdasarkan tipe
        if ($request->filled('type')) {
            $query->where('subject_type', $request->type);
        }

        // Sorting
        $sort = $request->get('sort', 'desc');
        $query->orderBy('created_at', $sort);

        $logs = $query->paginate(10);

        return view('public.activity_logs', compact('logs', 'sort'));
    }

    
    public function statistics()
    {
        $stats = [
            'families' => [
                'total' => Family::count(),
                'by_city' => Family::selectRaw('domicile, COUNT(*) as count')
                                  ->groupBy('domicile')
                                  ->orderBy('count', 'desc')
                                  ->get()
            ],
            'members' => [
                'total' => Member::count(),
                'by_gender' => Member::selectRaw('gender, COUNT(*) as count')
                                    ->groupBy('gender')
                                    ->get(),
                'by_generation' => Member::selectRaw('generation, COUNT(*) as count')
                                        ->groupBy('generation')
                                        ->orderBy('generation')
                                        ->get(),
                'by_status' => Member::selectRaw('status, COUNT(*) as count')
                                    ->groupBy('status')
                                    ->get(),
                'by_age_group' => Member::selectRaw('
                    CASE 
                        WHEN YEAR(CURDATE()) - YEAR(birth_date) < 18 THEN "Anak-anak"
                        WHEN YEAR(CURDATE()) - YEAR(birth_date) < 30 THEN "Remaja/Dewasa Muda"
                        WHEN YEAR(CURDATE()) - YEAR(birth_date) < 50 THEN "Dewasa"
                        WHEN YEAR(CURDATE()) - YEAR(birth_date) < 65 THEN "Paruh Baya"
                        ELSE "Lansia"
                    END as age_group,
                    COUNT(*) as count
                ')
                ->groupBy('age_group')
                ->get()
            ]
        ];
        
        return view('public.statistics', compact('stats'));
    }
     
}
