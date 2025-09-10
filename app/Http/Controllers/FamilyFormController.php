<?php

namespace App\Http\Controllers;

use App\Models\Family;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FamilyFormController extends Controller
{
    public function create()
    {
        // Redirect jika sudah login
        if (Auth::guard('family')->check()) {
            $family = Auth::guard('family')->user();
            return redirect()->route('families.show', $family)
                ->with('info', 'Anda sudah login sebagai admin keluarga.');
        }
        
        return view('public.family-form');
    }
    
    public function store(Request $request)
    {
        // Redirect jika sudah login
        if (Auth::guard('family')->check()) {
            $family = Auth::guard('family')->user();
            return redirect()->route('families.detail', $family);
        }
        
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:families|alpha_dash',
            'password' => 'required|string|min:8|confirmed',
            'domicile' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ], [
            'name.required' => 'Nama keluarga wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'username.alpha_dash' => 'Username hanya boleh berisi huruf, angka, dan garis bawah.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'domicile.required' => 'Domisili wajib diisi.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        DB::beginTransaction();
        
        try {
            $family = Family::create([
                'name' => $request->name,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'domicile' => $request->domicile,
                'description' => $request->description,
            ]);

            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'family',
                'subject_id' => $family->id,
                'description' => "Keluarga '{$family->name}' berhasil didaftarkan",
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            // Auto login
            Auth::guard('family')->login($family, true);

            DB::commit();

            return redirect()
                ->route('families.show', $family)
                ->with('success', "Selamat datang! Keluarga '{$family->name}' berhasil didaftarkan. Anda sekarang adalah admin keluarga ini.");

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Family registration error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat mendaftarkan keluarga. Silakan coba lagi.'])
                ->withInput();
        }
    }

    public function showLoginForm()
    {
        // Redirect jika sudah login
        if (Auth::guard('family')->check()) {
            $family = Auth::guard('family')->user();
            return redirect()->route('families.show', $family);
        }
        
        return view('public.auth.login');
    }

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
            return redirect()->back()->withErrors($validator)->withInput($request->only('username'));
        }

        try {
            $credentials = [
                'username' => $request->username,
                'password' => $request->password,
            ];
            
            $remember = $request->boolean('remember');

            if (Auth::guard('family')->attempt($credentials, $remember)) {
                $family = Auth::guard('family')->user();
                
                $request->session()->regenerate();
                
                ActivityLog::create([
                    'family_id' => $family->id,
                    'subject_type' => 'login',
                    'subject_id' => $family->id,
                    'description' => "Admin keluarga '{$family->name}' berhasil login",
                    'user_agent' => $request->userAgent(),
                    'ip_address' => $request->ip(),
                ]);

                return redirect()
                    ->route('families.show', $family)
                    ->with('success', "Selamat datang kembali, {$family->name}!");
            }

            return redirect()->back()
                ->withErrors(['username' => 'Username atau password salah.'])
                ->withInput($request->only('username'));

        } catch (\Exception $e) {
            Log::error('Login error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat login. Silakan coba lagi.'])
                ->withInput($request->only('username'));
        }
    }

    public function logout(Request $request)
    {
        $family = Auth::guard('family')->user();
        
        if ($family) {
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
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()
            ->route('home')
            ->with('success', 'Anda telah berhasil logout.');
    }

    public function edit(Family $family)
    {
        // ADMIN CHECK
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }
        
        if (Auth::guard('family')->id() !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit keluarga ini.');
        }

        return view('public.family-edit', compact('family'));
    }

    public function update(Request $request, Family $family)
    {
        // ADMIN CHECK
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        if (Auth::guard('family')->id() !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah keluarga ini.');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|alpha_dash|unique:families,username,' . $family->id,
            'domicile' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        try {
            $family->update($request->only(['name', 'username', 'domicile', 'description']));

            ActivityLog::create([
                'family_id' => $family->id,
                'subject_type' => 'family',
                'subject_id' => $family->id,
                'description' => "Data keluarga '{$family->name}' berhasil diperbarui",
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]);

            return redirect()
                ->route('families.show', $family)
                ->with('success', 'Data keluarga berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Family update error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data keluarga.'])
                ->withInput();
        }
    }

    public function destroy(Family $family)
    {
        // ADMIN CHECK
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')->with('error', 'Silakan login terlebih dahulu.');
        }
        
        if (Auth::guard('family')->id() !== $family->id) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus keluarga ini.');
        }

        try {
            $familyName = $family->name;
            
            // Logout sebelum menghapus
            Auth::guard('family')->logout();
            
            $family->delete();

            return redirect()
                ->route('home')
                ->with('success', "Keluarga '{$familyName}' berhasil dihapus.");

        } catch (\Exception $e) {
            Log::error('Family deletion error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus keluarga.');
        }
    }
}