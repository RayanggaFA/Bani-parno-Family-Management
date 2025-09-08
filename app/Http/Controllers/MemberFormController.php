<?php
// app/Http/Controllers/MemberFormController.php - CREATE NEW FILE

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Family;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MemberFormController extends Controller
{
    public function create()
    {
        $family = Auth::guard('family')->user();
        
        if (!$family) {
            return redirect()
                ->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        // Get potential parents (older generation members from same family)
        $potentialParents = Member::where('family_id', $family->id)
            ->orderBy('generation')
            ->orderBy('full_name')
            ->get();

        return view('public.member-form', compact('family', 'potentialParents'));
    }

    public function store(Request $request)
    {
        $family = Auth::guard('family')->user();
        
        if (!$family) {
            return redirect()->route('auth.login');
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'occupation' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'status' => 'required|in:Belum Menikah,Sudah Menikah,Janda/Duda,Memilih untuk tidak menjawab',
            'generation' => 'required|integer|min:1|max:5',
            'parent_id' => 'nullable|exists:members,id',
            'domicile_city' => 'required|string|max:255',
            'domicile_province' => 'required|string|max:255',
            'ktp_address' => 'required|string|max:500',
            'current_address' => 'required|string|max:500',
        ], [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'profile_photo.image' => 'File harus berupa gambar.',
            'profile_photo.max' => 'Ukuran foto maksimal 2MB.',
            'birth_place.required' => 'Tempat lahir wajib diisi.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
            'generation.required' => 'Generasi wajib dipilih.',
            'domicile_city.required' => 'Kota domisili wajib diisi.',
            'domicile_province.required' => 'Provinsi domisili wajib diisi.',
            'ktp_address.required' => 'Alamat KTP wajib diisi.',
            'current_address.required' => 'Alamat sekarang wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $memberData = $request->all();
            $memberData['family_id'] = $family->id;

            // Handle photo upload
            if ($request->hasFile('profile_photo')) {
                $photo = $request->file('profile_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $path = $photo->storeAs('profile-photos', $filename, 'public');
                $memberData['profile_photo'] = $path;
            }

            // Validate parent relationship
            if ($request->parent_id) {
                $parent = Member::where('id', $request->parent_id)
                    ->where('family_id', $family->id)
                    ->first();
                
                if (!$parent) {
                    return back()
                        ->withErrors(['parent_id' => 'Orang tua yang dipilih tidak valid.'])
                        ->withInput();
                }
            }

            $member = Member::create($memberData);

            // Log activity
            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'member',
                'subject_id' => $member->id,
                'description' => "Anggota '{$member->full_name}' ditambahkan ke keluarga '{$family->name}'",
            ]);

            return redirect()
                ->route('members.show', $member)
                ->with('success', 'Anggota keluarga berhasil ditambahkan.');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menambahkan anggota.'])
                ->withInput();
        }
    }

    public function edit(Member $member)
    {
        $family = Auth::guard('family')->user();
        
        if (!$family || $member->family_id !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit anggota ini.');
        }

        // Get potential parents (exclude current member and their descendants)
        $potentialParents = Member::where('family_id', $family->id)
            ->where('id', '!=', $member->id)
            ->orderBy('generation')
            ->orderBy('full_name')
            ->get();

        return view('public.member-edit', compact('member', 'family', 'potentialParents'));
    }

    public function update(Request $request, Member $member)
    {
        $family = Auth::guard('family')->user();
        
        if (!$family || $member->family_id !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah anggota ini.');
        }

        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'profile_photo' => 'nullable|image|mimes:jpeg,jpg,png,gif|max:2048',
            'birth_place' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'occupation' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'status' => 'required|in:Belum Menikah,Sudah Menikah,Janda/Duda,Memilih untuk tidak menjawab',
            'generation' => 'required|integer|min:1|max:5',
            'parent_id' => 'nullable|exists:members,id',
            'domicile_city' => 'required|string|max:255',
            'domicile_province' => 'required|string|max:255',
            'ktp_address' => 'required|string|max:500',
            'current_address' => 'required|string|max:500',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $memberData = $request->all();

            // Handle photo upload
            if ($request->hasFile('profile_photo')) {
                // Delete old photo if exists
                if ($member->profile_photo && Storage::disk('public')->exists($member->profile_photo)) {
                    Storage::disk('public')->delete($member->profile_photo);
                }

                $photo = $request->file('profile_photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $path = $photo->storeAs('profile-photos', $filename, 'public');
                $memberData['profile_photo'] = $path;
            }

            // Validate parent relationship
            if ($request->parent_id) {
                $parent = Member::where('id', $request->parent_id)
                    ->where('family_id', $family->id)
                    ->where('id', '!=', $member->id)
                    ->first();
                
                if (!$parent) {
                    return back()
                        ->withErrors(['parent_id' => 'Orang tua yang dipilih tidak valid.'])
                        ->withInput();
                }
            }

            $member->update($memberData);

            // Log activity
            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'member',
                'subject_id' => $member->id,
                'description' => "Data anggota '{$member->full_name}' diperbarui",
            ]);

            return redirect()
                ->route('members.show', $member)
                ->with('success', 'Data anggota berhasil diperbarui.');

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data anggota.'])
                ->withInput();
        }
    }

    public function destroy(Member $member)
    {
        $family = Auth::guard('family')->user();
        
        if (!$family || $member->family_id !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus anggota ini.');
        }

        try {
            $memberName = $member->full_name;

            // Delete photo if exists
            if ($member->profile_photo && Storage::disk('public')->exists($member->profile_photo)) {
                Storage::disk('public')->delete($member->profile_photo);
            }

            // Log activity before deletion
            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'member',
                'subject_id' => $member->id,
                'description' => "Anggota '{$memberName}' dihapus dari keluarga '{$family->name}'",
            ]);

            $member->delete();

            return redirect()
                ->route('members.index')
                ->with('success', "Anggota {$memberName} berhasil dihapus.");

        } catch (\Exception $e) {
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus anggota.']);
        }
    }
}