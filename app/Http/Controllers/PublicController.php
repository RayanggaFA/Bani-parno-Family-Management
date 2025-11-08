<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\Member;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class PublicController extends Controller
{
    public function index()
    {
        $totalFamilies = Family::count();
        
        // Safe member count check
        $totalMembers = 0;
        $recentMembers = collect([]);
        
        try {
            if (Schema::hasTable('members')) {
                $totalMembers = Member::count();
                $recentMembers = Member::with('family')
                    ->latest()
                    ->limit(6)
                    ->get();
            }
        } catch (\Exception $e) {
            \Log::warning('Error loading members: ' . $e->getMessage());
        }
        
        return view('public.home', compact('totalFamilies', 'totalMembers', 'recentMembers'));
    }

    public function families(Request $request)
{
    $query = Family::withCount('members');

    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('occupation', 'like', "%{$search}%")
              ->orWhere('domicile_city', 'like', "%{$search}%")
              ->orWhere('domicile_province', 'like', "%{$search}%");
        });
    }

    $families = $query->orderBy('name')->paginate(12);

    return view('public.families', compact('families'));
}

    public function family(Family $family)
    {
        // Check if user is admin
        $isAdmin = Auth::guard('family')->check() && Auth::guard('family')->id() === $family->id;
        
        // Get statistics with proper database columns
        $stats = $this->getFamilyStatistics($family);
        
        // Get members with proper relations
        $allMembers = collect([]);
        $rootMembers = collect([]);
        
        try {
            if (Schema::hasTable('members')) {
                // Load members with parent and children relations
                $family->load(['members' => function($query) {
                $query->orderBy('full_name');
            }]);
                $allMembers = $family->members;
                $rootMembers = $family->members->where('parent_id', null);
            }
        } catch (\Exception $e) {
            \Log::warning('Error loading family members: ' . $e->getMessage());
        }
        
        // FIXED: Use existing view
        return view('public.family-detail', compact('family', 'isAdmin', 'stats', 'allMembers', 'rootMembers'));
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
              ->orWhere('birth_place', 'like', '%' . $request->search . '%')
              ->orWhere('domicile_city', 'like', '%' . $request->search . '%')
            ->orWhere('domicile_province', 'like', '%' . $request->search . '%');
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
        try {
            $member->load(['family', 'parent', 'children']);
            
            // Find siblings
            $siblings = collect();
            if ($member->parent_id) {
                $siblings = Member::where('parent_id', $member->parent_id)
                                ->where('id', '!=', $member->id)
                                ->get();
            } else {
                $siblings = Member::where('family_id', $member->family_id)
                                ->whereNull('parent_id')
                                ->where('id', '!=', $member->id)
                                ->get();
            }
        } catch (\Exception $e) {
            \Log::warning('Error loading member details: ' . $e->getMessage());
            $siblings = collect();
        }
        
        return view('public.member-detail', compact('member', 'siblings'));
    }

    public function show($id)
    {
        $family = Family::with('members')->findOrFail($id);
        // Use same logic as family() method
        $isAdmin = Auth::guard('family')->check() && Auth::guard('family')->id() === $family->id;
        $stats = $this->getFamilyStatistics($family);
        $allMembers = $family->members ?? collect([]);
        $rootMembers = $family->members->where('parent_id', null) ?? collect([]);
        
        return view('public.family-detail', compact('family', 'isAdmin', 'stats', 'allMembers', 'rootMembers'));
    }

    public function familyTree(Family $family)
    {
        try {
            if (Schema::hasTable('members')) {
                $family->load(['members.parent', 'members.children']);
                
                // Group members by generation
                $generationData = collect();
                
                foreach ($family->members as $member) {
                    $generation = $member->generation ?? 1; // Default ke generasi 1 jika null
                    
                    if (!$generationData->has($generation)) {
                        $generationData->put($generation, collect());
                    }
                    
                    $generationData->get($generation)->push($member);
                }
                
                // Sort by generation number
                $generationData = $generationData->sortKeys();
                
            } else {
                $generationData = collect();
            }
            
            // Calculate statistics
            $stats = [
                'total_members' => $family->members->count(),
                'male_count' => $family->members->whereIn('gender', ['male', 'Laki-laki'])->count(),
                'female_count' => $family->members->whereIn('gender', ['female', 'Perempuan'])->count(),
            ];
            
        } catch (\Exception $e) {
            \Log::warning('Error loading family tree: ' . $e->getMessage());
            $generationData = collect();
            $stats = [
                'total_members' => 0,
                'male_count' => 0,
                'female_count' => 0,
            ];
        }
        
        return view('public.family-tree', compact('family', 'generationData', 'stats'));
    }


    public function activityHistory(Request $request)
    {
        try {
            if (!Schema::hasTable('activity_logs')) {
                $logs = collect([])->paginate(10);
                $sort = $request->get('sort', 'desc');
                return view('public.activity_logs', compact('logs', 'sort'));
            }

            $query = ActivityLog::with('family');

            if ($request->filled('type')) {
                $query->where('subject_type', $request->type);
            }

            if ($request->filled('family_id')) {
                $query->where('family_id', $request->family_id);
            }

            $sort = $request->get('sort', 'desc');
            $query->orderBy('created_at', $sort);

            $logs = $query->paginate(10);
            
        } catch (\Exception $e) {
            \Log::warning('Error loading activity history: ' . $e->getMessage());
            $logs = collect([])->paginate(10);
            $sort = $request->get('sort', 'desc');
        }

        return view('public.activity_logs', compact('logs', 'sort'));
    }

    private function getMemberNameColumn()
    {
        if (!Schema::hasTable('members')) {
            return 'id';
        }
        
        $columns = Schema::getColumnListing('members');
        
        // Priority order: name -> full_name -> first_name -> member_name -> id
        if (in_array('name', $columns)) {
            return 'name';
        } elseif (in_array('full_name', $columns)) {
            return 'full_name';
        } elseif (in_array('first_name', $columns)) {
            return 'first_name';
        } elseif (in_array('member_name', $columns)) {
            return 'member_name';
        }
        
        return 'id'; // fallback
    }
    
   
    public function statistics()
    {
        try {
            $stats = [
                'families' => [
                    'total' => Family::count(),
                    'by_city' => Family::selectRaw('domicile, COUNT(*) as count')
                                      ->groupBy('domicile')
                                      ->orderBy('count', 'desc')
                                      ->get()
                ]
            ];

            if (Schema::hasTable('members')) {
                $stats['members'] = [
                    'total' => Member::count(),
                    'by_gender' => Member::selectRaw('gender, COUNT(*) as count')
                                        ->groupBy('gender')
                                        ->get(),
                    'by_status' => Member::selectRaw('marital_status, COUNT(*) as count')
                                        ->groupBy('marital_status')
                                        ->get(),
                ];

                $stats['members']['by_age_group'] = Member::selectRaw('
                    CASE 
                        WHEN birth_date IS NULL THEN "Tidak Diketahui"
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 18 THEN "Anak-anak"
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 30 THEN "Remaja/Dewasa Muda"
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 50 THEN "Dewasa"
                        WHEN TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) < 65 THEN "Paruh Baya"
                        ELSE "Lansia"
                    END as age_group,
                    COUNT(*) as count
                ')
                ->groupBy('age_group')
                ->get();
            } else {
                $stats['members'] = [
                    'total' => 0,
                    'by_gender' => collect([]),
                    'by_status' => collect([]),
                    'by_age_group' => collect([])
                ];
            }
            
        } catch (\Exception $e) {
            \Log::warning('Error loading statistics: ' . $e->getMessage());
            $stats = [
                'families' => ['total' => 0, 'by_city' => collect([])],
                'members' => ['total' => 0, 'by_gender' => collect([]), 'by_status' => collect([]), 'by_age_group' => collect([])]
            ];
        }
        
        return view('public.statistics', compact('stats'));
    }

    /**
     * Helper method to get family statistics
     */
    private function getFamilyStatistics(Family $family): array
{
    try {
        if (!Schema::hasTable('members')) {
            return [
                'total_members' => 0,
                'male_members' => 0,
                'female_members' => 0,
                'married_members' => 0,
                'alive_members' => 0,
                'recent_activities' => collect([]),
            ];
        }

        // Cek kolom yang ada di members table
        $columns = Schema::getColumnListing('members');

        $stats = [
            'total_members' => $family->members()->count(),
        ];

        // Only query columns that exist
        if (in_array('gender', $columns)) {
            $stats['male_members'] = $family->members()->where('gender', 'male')->count();
            $stats['female_members'] = $family->members()->where('gender', 'female')->count();
        } else {
            $stats['male_members'] = 0;
            $stats['female_members'] = 0;
        }

        // FIX: Cek apakah kolom marital_status ada
        if (in_array('marital_status', $columns)) {
            $stats['married_members'] = $family->members()->where('marital_status', 'married')->count();
        } else {
            $stats['married_members'] = 0;
        }

        // FIX: Cek apakah kolom death_date ada
        if (in_array('death_date', $columns)) {
            $stats['alive_members'] = $family->members()->whereNull('death_date')->count();
        } else {
            $stats['alive_members'] = $stats['total_members']; // Assume all alive
        }

        if (Schema::hasTable('activity_logs')) {
            $stats['recent_activities'] = $family->activityLogs()->latest()->limit(5)->get();
        } else {
            $stats['recent_activities'] = collect([]);
        }

        return $stats;
        
    } catch (\Exception $e) {
        \Log::warning('Error getting family statistics: ' . $e->getMessage());
        return [
            'total_members' => 0,
            'male_members' => 0,
            'female_members' => 0,
            'married_members' => 0,
            'alive_members' => 0,
            'recent_activities' => collect([]),
        ];
    }
}

    /**
     * Get member level in family tree
     */
    private function getMemberLevel(Member $member, $level = 1): int
    {
        if ($member->parent_id) {
            $parent = Member::find($member->parent_id);
            if ($parent) {
                return $this->getMemberLevel($parent, $level + 1);
            }
        }
        return $level;
    }
}
?>