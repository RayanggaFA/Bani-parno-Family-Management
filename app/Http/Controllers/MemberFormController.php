<?php
// ================================================================
// 1. MIDDLEWARE UNTUK ADMIN ACCESS - app/Http/Middleware/FamilyAdminMiddleware.php
// ================================================================

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class FamilyAdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated with family guard
        if (!Auth::guard('family')->check()) {
            return redirect()->route('auth.login')
                ->with('error', 'Silakan login terlebih dahulu untuk mengakses halaman ini.');
        }

        $family = Auth::guard('family')->user();
        
        // Check if trying to access other family's data
        if ($request->route('family')) {
            $targetFamily = $request->route('family');
            
            // If target family is different from logged in family
            if ($targetFamily->id !== $family->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengelola keluarga ini. Anda hanya dapat mengelola keluarga Anda sendiri.');
            }
        }

        // Check if trying to access other family's member data
        if ($request->route('member')) {
            $targetMember = $request->route('member');
            
            // If member doesn't belong to logged in family
            if ($targetMember->family_id !== $family->id) {
                abort(403, 'Anda tidak memiliki akses untuk mengelola anggota keluarga ini. Anda hanya dapat mengelola anggota keluarga Anda sendiri.');
            }
        }

        return $next($request);
    }
}

// ================================================================
// 2. UPDATE FAMILY MODEL - app/Models/Family.php (ADD ADMIN METHODS)
// ================================================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Family extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username', 
        'password',
        'domicile',
        'description',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationships
    public function members()
    {
        return $this->hasMany(Member::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }

    // Custom authentication - use username instead of email
    public function getAuthIdentifierName()
    {
        return 'username';
    }

    // ADMIN ROLE METHODS
    /**
     * Check if this family can manage another family (only themselves)
     */
    public function canManageFamily(Family $targetFamily): bool
    {
        return $this->id === $targetFamily->id;
    }

    /**
     * Check if this family can manage a member (only their own members)
     */
    public function canManageMember(Member $member): bool
    {
        return $this->id === $member->family_id;
    }

    /**
     * Check if this family is admin of themselves (always true)
     */
    public function isAdminOf(Family $family): bool
    {
        return $this->id === $family->id;
    }

    /**
     * Get family statistics for dashboard
     */
    public function getStatistics(): array
    {
        return [
            'total_members' => $this->members()->count(),
            'male_members' => $this->members()->where('gender', 'male')->count(),
            'female_members' => $this->members()->where('gender', 'female')->count(),
            'married_members' => $this->members()->where('marital_status', 'married')->count(),
            'recent_activities' => $this->activityLogs()->latest()->limit(10)->get(),
        ];
    }

    // Helper methods
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    /**
     * Get admin name (same as family name)
     */
    public function getAdminNameAttribute()
    {
        return $this->name;
    }

    /**
     * Scope for filtering families by admin access
     */
    public function scopeAdministeredBy($query, Family $admin)
    {
        return $query->where('id', $admin->id);
    }
}

// ================================================================
// 3. REGISTER MIDDLEWARE - bootstrap/app.php (Laravel 11)
// ================================================================

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Register middleware alias
        $middleware->alias([
            'auth' => \Illuminate\Auth\Middleware\Authenticate::class,
            'family.admin' => \App\Http\Middleware\FamilyAdminMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();

// ================================================================
// 4. FIXED ROUTES WITH PROPER ADMIN MIDDLEWARE - routes/web.php
// ================================================================

use App\Http\Controllers\PublicController;
use App\Http\Controllers\FamilyFormController;
use App\Http\Controllers\MemberFormController;
use Illuminate\Support\Facades\Route;

// Public Interface Routes
Route::get('/', [PublicController::class, 'index'])->name('home');

// Authentication Routes
Route::get('/login', [FamilyFormController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [FamilyFormController::class, 'login'])->name('auth.login.submit');

// Family Registration (PUBLIC ACCESS)
Route::get('/daftar', [FamilyFormController::class, 'create'])->name('families.create');
Route::post('/daftar', [FamilyFormController::class, 'store'])->name('families.store');

// Family Management Routes
Route::prefix('keluarga')->name('families.')->group(function () {
    // Public routes (anyone can view)
    Route::get('/', [PublicController::class, 'families'])->name('index');
    Route::get('/{family}', [PublicController::class, 'family'])->name('show');
    Route::get('/{family}/pohon-keluarga', [PublicController::class, 'familyTree'])->name('tree');
    
    // ADMIN-ONLY routes (can only manage their own family)
    Route::middleware('family.admin')->group(function () {
        Route::get('/{family}/edit', [FamilyFormController::class, 'edit'])->name('edit');
        Route::put('/{family}', [FamilyFormController::class, 'update'])->name('update');
        Route::delete('/{family}', [FamilyFormController::class, 'destroy'])->name('destroy');
    });
});

// Member Management Routes  
Route::prefix('anggota')->name('members.')->group(function () {
    // Public routes (anyone can view)
    Route::get('/', [PublicController::class, 'members'])->name('index');
    Route::get('/{member}', [PublicController::class, 'member'])->name('show');
    
    // ADMIN-ONLY routes (can only manage their own family members)
    Route::middleware('family.admin')->group(function () {
        Route::get('/tambah', [MemberFormController::class, 'create'])->name('create');
        Route::post('/', [MemberFormController::class, 'store'])->name('store');
        Route::get('/{member}/edit', [MemberFormController::class, 'edit'])->name('edit');
        Route::put('/{member}', [MemberFormController::class, 'update'])->name('update');
        Route::delete('/{member}', [MemberFormController::class, 'destroy'])->name('destroy');
    });
});

// ADMIN-ONLY logout (must be logged in)
Route::middleware('family.admin')->group(function () {
    Route::post('/logout', [FamilyFormController::class, 'logout'])->name('auth.logout');
});

// Activity History Route (public)
Route::get('/riwayat-aktivitas', [PublicController::class, 'activityHistory'])->name('activity.history');

// ================================================================
// 5. UPDATE MEMBER FORM CONTROLLER - app/Http/Controllers/MemberFormController.php
// ================================================================

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
    public function create()
    {
        $family = Auth::guard('family')->user();
        
        // Only show members from the same family for parent selection
        $potentialParents = $family->members()->get();
        
        return view('public.member-form', compact('family', 'potentialParents'));
    }

    public function store(Request $request)
    {
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
                        ->withErrors(['parent_id' => 'Parent yang dipilih tidak valid.'])
                        ->withInput();
                }
            }

            $member = Member::create([
                'family_id' => $family->id, // Force to current family
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
                ->route('members.show', $member)
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
        $family = Auth::guard('family')->user();
        
        // This check is now handled by middleware, but good to have double-check
        if (!$family->canManageMember($member)) {
            abort(403, 'Anda tidak memiliki akses untuk mengedit anggota keluarga ini.');
        }
        
        $potentialParents = $family->members()->where('id', '!=', $member->id)->get();
        
        return view('public.member-edit', compact('member', 'family', 'potentialParents'));
    }

    public function update(Request $request, Member $member)
    {
        $family = Auth::guard('family')->user();
        
        if (!$family->canManageMember($member)) {
            abort(403, 'Anda tidak memiliki akses untuk mengubah anggota keluarga ini.');
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
            // Validate parent belongs to same family
            if ($request->parent_id) {
                $parent = Member::find($request->parent_id);
                if ($parent && $parent->family_id !== $family->id) {
                    return redirect()->back()
                        ->withErrors(['parent_id' => 'Parent yang dipilih tidak valid.'])
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
                ->route('members.show', $member)
                ->with('success', 'Data anggota keluarga berhasil diperbarui.');

        } catch (\Exception $e) {
            Log::error('Member update error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui data anggota keluarga.'])
                ->withInput();
        }
    }

    public function destroy(Member $member)
    {
        $family = Auth::guard('family')->user();
        
        if (!$family->canManageMember($member)) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus anggota keluarga ini.');
        }

        try {
            $memberName = $member->name;
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
                ->route('members.index')
                ->with('success', "Anggota keluarga '{$memberName}' berhasil dihapus.");

        } catch (\Exception $e) {
            Log::error('Member deletion error: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menghapus anggota keluarga.');
        }
    }
}

