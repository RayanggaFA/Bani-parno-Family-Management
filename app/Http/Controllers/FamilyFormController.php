<?php
// app/Http/Controllers/FamilyFormController.php - FIXED VERSION WITH PROPER REDIRECTS

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FamilyFormController extends Controller
{
    /**
     * Show the family registration form
     */
    public function create()
    {
        return view('public.family-form');
    }
    
    /**
     * Store a new family and auto-login
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:families|alpha_dash',
            'password' => 'required|string|min:8|confirmed',
            'domicile' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'terms' => 'required|accepted',
        ], [
            'name.required' => 'Nama keluarga wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dan garis bawah.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'domicile.required' => 'Domisili wajib diisi.',
            'terms.required' => 'Anda harus menyetujui ketentuan.',
            'terms.accepted' => 'Anda harus menyetujui ketentuan.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $family = Family::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'domicile' => $request->domicile,
                'description' => $request->description,
            ]);

            // Log activity
            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'family',
                'subject_id' => $family->id,
                'description' => "Keluarga '{$family->name}' berhasil didaftarkan",
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            // Auto login the family admin
            Auth::guard('family')->login($family, true); // Remember login

            // Redirect immediately to dashboard with success message
            return redirect()
                ->route('families.show', $family)
                ->with('success', "Selamat datang! Keluarga '{$family->name}' berhasil didaftarkan. Anda sekarang adalah admin keluarga ini.");

        } catch (\Exception $e) {
            \Log::error('Family registration error: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat mendaftarkan keluarga. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Show family edit form
     */
    public function edit(Family $family)
    {
        // Check if current family admin owns this family
        if (Auth::guard('family')->id() !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit keluarga ini.');
        }

        return view('public.family-edit', compact('family'));
    }

    /**
     * Update family data
     */
    public function update(Request $request, Family $family)
    {
        // Check if current family admin owns this family
        if (Auth::guard('family')->id() !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah keluarga ini.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:families,username,' . $family->id,
            'domicile' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama keluarga wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dan garis bawah.',
            'domicile.required' => 'Domisili wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        try {
            $changes = [];
            
            if ($family->name !== $request->name) $changes[] = 'nama';
            if ($family->username !== $request->username) $changes[] = 'username';
            if ($family->domicile !== $request->domicile) $changes[] = 'domisili';
            if ($family->description !== $request->description) $changes[] = 'deskripsi';

            $family->update([
                'name' => $request->name,
                'username' => $request->username,
                'domicile' => $request->domicile,
                'description' => $request->description,
            ]);

            // Log activity if there were changes
            if (!empty($changes)) {
                ActivityLog::create([
                    'family_id' => $family->id,
                    'subject_type' => 'family',
                    'subject_id' => $family->id,
                    'description' => "Data keluarga '{$family->name}' diubah: " . implode(', ', $changes),
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                ]);
            }

            return redirect()
                ->route('families.show', $family)
                ->with('success', 'Data keluarga berhasil diperbarui.');

        } catch (\Exception $e) {
            \Log::error('Family update error: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data keluarga.'])
                ->withInput();
        }
    }

    /**
     * Delete family
     */
    public function destroy(Family $family)
    {
        // Check if current family admin owns this family
        if (Auth::guard('family')->id() !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus keluarga ini.');
        }

        try {
            $familyName = $family->name;
            
            // Log activity before deletion
            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'family',
                'subject_id' => $family->id,
                'description' => "Keluarga '{$familyName}' dihapus dari sistem",
                'user_agent' => request()->userAgent(),
                'ip_address' => request()->ip(),
            ]);

            // Delete all members first (cascade should handle this, but being explicit)
            $family->members()->delete();
            
            // Delete the family
            $family->delete();

            // Logout the admin
            Auth::guard('family')->logout();

            return redirect()
                ->route('families.index')
                ->with('success', "Keluarga {$familyName} berhasil dihapus dari sistem.");

        } catch (\Exception $e) {
            \Log::error('Family deletion error: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghapus keluarga.']);
        }
    }

    /**
     * Show login form
     */
    public function showLoginForm()
    {
        return view('public.auth.login');
    }

    /**
     * Handle login attempt with proper redirect
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('username'));
        }

        $credentials = $request->only('username', 'password');
        $remember = $request->boolean('remember');

        try {
            if (Auth::guard('family')->attempt($credentials, $remember)) {
                $family = Auth::guard('family')->user();
                
                // Log login activity
                ActivityLog::create([
                    'family_id' => $family->id,
                    'subject_type' => 'login',
                    'subject_id' => $family->id,
                    'description' => "Admin keluarga '{$family->name}' berhasil login",
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                ]);

                // Regenerate session for security
                $request->session()->regenerate();

                // Redirect to intended page or family dashboard
                $intendedUrl = $request->session()->get('url.intended', route('families.show', $family));
                
                return redirect()
                    ->to($intendedUrl)
                    ->with('success', "Selamat datang kembali, {$family->name}!");
            }

            // Login failed
            return back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->withInput($request->only('username'));

        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());
            return back()
                ->withErrors(['error' => 'Terjadi kesalahan saat login. Silakan coba lagi.'])
                ->withInput($request->only('username'));
        }
    }

    /**
     * Handle logout
     */
    public function logout(Request $request)
    {
        $family = Auth::guard('family')->user();
        
        if ($family) {
            // Log logout activity
            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'logout',
                'subject_id' => $family->id,
                'description' => "Admin keluarga '{$family->name}' logout dari sistem",
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);
        }

        Auth::guard('family')->logout();
        
        // Invalidate the session
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()
            ->route('home')
            ->with('success', 'Anda telah berhasil logout.');
    }
}

