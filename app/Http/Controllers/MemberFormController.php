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

class MemberFormController extends Controller
{
    public function create(Family $family)
    {
        // Admin check using FAMILY GUARD
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login sebagai admin keluarga untuk menambah anggota.');
        }
        
        $authFamily = Auth::guard('family')->user();
        
        // Check if authenticated family can manage this family
        if ($authFamily->id !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk menambah anggota pada keluarga ini.');
        }
        
        // FIX: Check kolom apa yang ada di members table
        $columns = Schema::getColumnListing('members');
        \Log::info('Members table columns:', $columns);
        
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
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login sebagai admin keluarga.');
        }
        
        $family = Auth::guard('family')->user();
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'gender' => 'required|in:male,female',
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
        ], [
            'name.required' => 'Nama anggota keluarga wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin harus male atau female.',
            'birth_date.before' => 'Tanggal lahir harus sebelum hari ini.',
            'death_date.after' => 'Tanggal meninggal harus setelah tanggal lahir.',
            'marital_status.required' => 'Status pernikahan wajib dipilih.',
            'parent_id.exists' => 'Orang tua yang dipilih tidak valid.',
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

            $member = Member::create([
                'family_id' => $family->id,
                'name' => $request->name,
                'nickname' => $request->nickname,
                'gender' => $request->gender,
                'birth_date' => $request->birth_date,
                'birth_place' => $request->birth_place,
                'death_date' => $request->death_date,
                'death_place' => $request->death_place,
                'marital_status' => $request->marital_status,
                'occupation' => $request->occupation,
                'address' => $request->address,
                'phone' => $request->phone,
                'parent_id' => $request->parent_id,
                'notes' => $request->notes,
            ]);

            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'member',
                'subject_id' => $member->id,
                'description' => "Anggota keluarga '{$member->name}' berhasil ditambahkan oleh admin {$family->name}",
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            DB::commit();

            return redirect()
                ->route('families.show', $family)
                ->with('success', "Anggota keluarga '{$member->name}' berhasil ditambahkan.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Member creation error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menambahkan anggota keluarga.'])
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
        
        $potentialParents = $family->members()->orderBy('full_name')->get();
        
        return view('public.members.edit', compact('member', 'family', 'potentialParents'));
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
            'name' => 'required|string|max:255',
            'nickname' => 'nullable|string|max:100',
            'gender' => 'required|in:male,female',
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

            $member->update($request->only([
                'name', 'nickname', 'gender', 'birth_date', 'birth_place',
                'death_date', 'death_place', 'marital_status', 'occupation',
                'address', 'phone', 'parent_id', 'notes'
            ]));

            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'member',
                'subject_id' => $member->id,
                'description' => "Data anggota keluarga '{$member->name}' berhasil diperbarui oleh admin {$family->name}",
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

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
            $memberName = $member->name;
            
            // Check if member has children - prevent deletion if has children
            if ($member->children()->count() > 0) {
                return redirect()->back()
                    ->with('error', "Tidak dapat menghapus {$memberName} karena masih memiliki anak dalam silsilah keluarga. Hapus atau pindahkan anak-anak terlebih dahulu.");
            }
            
            $member->delete();

            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'member',
                'subject_id' => null,
                'description' => "Anggota keluarga '{$memberName}' berhasil dihapus oleh admin {$family->name}",
                'user_agent' => request()->userAgent(),
                'ip_address' => request()->ip(),
            ]);

            return redirect()
                ->route('families.show', $family)
                ->with('success', "Anggota keluarga '{$memberName}' berhasil dihapus.");

        } catch (\Exception $e) {
            Log::error('Member deletion error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus anggota keluarga.');
        }
    }
}