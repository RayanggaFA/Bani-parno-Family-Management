<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class Family extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username', 
        'password',
        'domicile',
        'description',
        'photo', 
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

    
    public function getPhotoUrlAttribute()
    {
        if ($this->photo) {
            return Storage::url($this->photo);
        }
        
        // Default avatar dengan initial nama keluarga
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&size=200&background=4F46E5&color=fff';
    }

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
            'male_members' => $this->members()->where('gender', 'Laki-laki')->count(),
            'female_members' => $this->members()->where('gender', 'Perempuan')->count(),
            'married_members' => $this->members()->where('status', 'Sudah Menikah')->count(),
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