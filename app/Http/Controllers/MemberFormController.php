<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class MemberFormController extends Controller
{
    public function create()  // ← FIXED: Hapus parameter Family $family
    {
        // TETAP ADA: Auth check - harus login dulu!
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login sebagai admin keluarga untuk menambah anggota.');
        }
        
        // AMBIL FAMILY dari user yang sedang login
        $family = Auth::guard('family')->user();
        
        // Check kolom apa yang ada di members table
        $columns = Schema::getColumnListing('members');
        Log::info('Members table columns:', $columns);
        
        // Determine correct name column
        $orderByColumn = 'id'; // fallback default
        if (in_array('name', $columns)) {
            $orderByColumn = 'name';
        } elseif (in_array('full_name', $columns)) {
            $orderByColumn = 'full_name';
        } elseif (in_array('first_name', $columns)) {
            $orderByColumn = 'first_name';
        } elseif (in_array('member_name', $columns)) {
            $orderByColumn = 'member_name';
        }
        
        $potentialParents = $family->members()->orderBy($orderByColumn)->get();
        
        return view('public.member-form', compact('family', 'potentialParents'));
    }


    public function store(Request $request)
    {
        // TETAP ADA: Auth check
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login sebagai admin keluarga.');
        }
        
        $family = Auth::guard('family')->user();
        
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'nullable|date|before:today',
            'birth_place' => 'nullable|string|max:255',
            'marital_status' => 'required|in:single,married,divorced,widowed,prefer_not_to_answer',
            'occupation' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'domicile_city' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:members,id',
            'notes' => 'nullable|string|max:1000',
            'ktp_address' => 'required|string|max:500',
            'domicile_province' => 'required|string|max:255',
            'generation' => 'required|integer|in:1,2,3,4,5',
            'current_address' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // TAMBAHAN: Support upload foto
        ], [
            'name.required' => 'Nama anggota keluarga wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin harus male atau female.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'marital_status.required' => 'Status pernikahan wajib dipilih.',
            'parent_id.exists' => 'Orang tua yang dipilih tidak valid.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        
        try {
            // Validate parent belongs to same family
            if ($request->parent_id) {
                $parent = Member::find($request->parent_id);
                if ($parent && $parent->family_id !== $family->id) {
                    return redirect()->back()
                        ->withErrors(['parent_id' => 'Orang tua yang dipilih tidak valid.'])
                        ->withInput();
                }
            }

            // Handle profile photo upload
            $profilePhotoPath = null;
            if ($request->hasFile('profile_photo')) {
                $profilePhotoPath = $request->file('profile_photo')->store('member-photos', 'public');
            }

            $member = Member::create([
                'family_id' => $family->id,
                'full_name' => $request->full_name,
                'nickname' => $request->nickname,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'marital_status' => $request->marital_status,
                'occupation' => $request->occupation,
                'phone' => $request->phone,
                'parent_id' => $request->parent_id,
                'domicile_city' => $request->domicile_city,
                'domicile_province' => $request->domicile_province,
                'notes' => $request->notes,
                'ktp_address' => $request->ktp_address,
                'current_address' => $request->current_address,
                'generation' => $request->generation,
                'profile_photo' => $profilePhotoPath, // TAMBAHAN: Save foto
            ]);

            // SIMPLIFIED: Activity log tanpa user_agent/ip_address (sesuai diskusi sebelumnya)
            try {
                ActivityLog::create([
                    'family_id' => $family->id,
                    'subject_type' => 'member',
                    'subject_id' => $member->id,
                    'description' => "Anggota keluarga '{$member->name}' berhasil ditambahkan oleh admin {$family->name}",
                ]);
            } catch (\Exception $e) {
                Log::warning('Activity log creation failed: ' . $e->getMessage());
                // Don't fail member creation if activity log fails
            }

            DB::commit();

            return redirect()
                ->route('families.show', $family)
                ->with('success', "Anggota keluarga '{$member->name}' berhasil ditambahkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Member creation error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menambahkan anggota keluarga: ' . $e->getMessage()])
                ->withInput();
        }
    }

     public function edit(Member $member)
    {
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login sebagai admin keluarga.');
        }
        
        $family = Auth::guard('family')->user();
        
        // Check ownership
        if ($member->family_id !== $family->id) {
            abort(403, 'Anda hanya dapat mengedit anggota keluarga Anda sendiri.');
        }
        
        // FIX: Use same logic as create() for determining column name
        $columns = Schema::getColumnListing('members');
        $orderByColumn = 'id';
        if (in_array('name', $columns)) {
            $orderByColumn = 'name';
        } elseif (in_array('full_name', $columns)) {
            $orderByColumn = 'full_name';
        }
        
        $potentialParents = $family->members()
            ->where('id', '!=', $member->id)
            ->orderBy($orderByColumn)
            ->get();
        
        return view('public.member-edit', compact('member', 'family', 'potentialParents'));
    }

    public function update(Request $request, Member $member)
    {
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login sebagai admin keluarga.');
        }
        
        $family = Auth::guard('family')->user();
        
        if ($member->family_id !== $family->id) {
            abort(403, 'Anda hanya dapat mengedit anggota keluarga Anda sendiri.');
        }

        $validator = Validator::make($request->all(), [
    'full_name' => 'required|string|max:255',
    'nickname' => 'nullable|string|max:100',
    'gender' => 'required|in:Laki-laki,Perempuan',
    'birth_date' => 'nullable|date|before:today',
    'birth_place' => 'nullable|string|max:255',
    'death_date' => 'nullable|date|after:birth_date',
    'death_place' => 'nullable|string|max:255',
    'marital_status' => 'required|in:single,married,divorced,widowed',
    'occupation' => 'nullable|string|max:255',
    'address' => 'nullable|string|max:500',
    'phone' => 'nullable|string|max:20',
    'parent_id' => 'nullable|exists:members,id',
    'notes' => 'nullable|string|max:1000',
    'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            // Validate parent belongs to same family and not circular reference
            if ($request->parent_id) {
                $parent = Member::find($request->parent_id);
                if ($parent && $parent->family_id !== $family->id) {
                    return redirect()->back()
                        ->withErrors(['parent_id' => 'Orang tua yang dipilih tidak valid.'])
                        ->withInput();
                }
                
                // Prevent circular reference
                if ($request->parent_id == $member->id) {
                    return redirect()->back()
                        ->withErrors(['parent_id' => 'Anggota tidak bisa menjadi orang tua dari dirinya sendiri.'])
                        ->withInput();
                }
            }

            // Handle profile photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($member->profile_photo) {
                    \Storage::disk('public')->delete($member->profile_photo);
                }
                $member->profile_photo = $request->file('profile_photo')->store('member-photos', 'public');
            }

            $member->update($request->only([
    'full_name', 'nickname', 'gender', 'birth_date', 'birth_place',
    'death_date', 'death_place', 'marital_status', 'occupation',
    'address', 'phone', 'parent_id', 'notes'
]));


            // Update profile photo if uploaded
            if ($request->hasFile('profile_photo')) {
                $member->save();
            }

            // SIMPLIFIED: Activity log
            try {
                ActivityLog::create([
                    'family_id' => $family->id,
                    'subject_type' => 'member',
                    'subject_id' => $member->id,
                    'description' => "Data anggota keluarga '{$member->name}' berhasil diperbarui oleh admin {$family->name}",
                ]);
            } catch (\Exception $e) {
                Log::warning('Activity log update failed: ' . $e->getMessage());
            }

            return redirect()
                ->route('families.show', $family)
                ->with('success', "Data anggota keluarga '{$member->name}' berhasil diperbarui.");

        } catch (\Exception $e) {
            Log::error('Member update error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data anggota keluarga.'])
                ->withInput();
        }
    }

    public function destroy(Member $member)
{
    if (!Auth::guard('family')->check()) {
        return redirect()->route('auth.login')->with('error', 'Silakan login sebagai admin keluarga.');
    }
    
    $family = Auth::guard('family')->user();
    
    if ($member->family_id !== $family->id) {
        abort(403, 'Anda hanya dapat menghapus anggota keluarga Anda sendiri.');
    }

    try {
        $memberName = $member->full_name; // FIXED: Menggunakan full_name
        
        // Check if member has children - prevent deletion if has children
        if ($member->children()->count() > 0) {
            return redirect()->back()
                ->with('error', "Tidak dapat menghapus {$memberName} karena masih memiliki anak dalam silsilah keluarga. Hapus atau pindahkan anak-anak terlebih dahulu.");
        }

        // Delete profile photo if exists
        if ($member->profile_photo) {
            \Storage::disk('public')->delete($member->profile_photo);
        }
        
        $member->delete();

        // SIMPLIFIED: Activity log
        try {
            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'member',
                'subject_id' => null,
                'description' => "Anggota keluarga '{$memberName}' berhasil dihapus oleh admin {$family->name}",
            ]);
        } catch (\Exception $e) {
            \Log::warning('Activity log delete failed: ' . $e->getMessage());
        }

        return redirect()
            ->route('families.show', $family)
            ->with('success', "Anggota keluarga '{$memberName}' berhasil dihapus.");

    } catch (\Exception $e) {
        \Log::error('Member deletion error: ' . $e->getMessage());
        
        return redirect()->back()
            ->with('error', 'Terjadi kesalahan saat menghapus anggota keluarga.');
    }

    }
}