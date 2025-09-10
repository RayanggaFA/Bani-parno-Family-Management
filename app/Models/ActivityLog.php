<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'subject_type',
        'subject_id',
        'description',
        'user_agent',
        'ip_address',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ===================== RELATIONSHIPS =====================
    
    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function subject()
    {
        return $this->morphTo();
    }

    // ===================== SCOPES =====================
    
    public function scopeForFamily($query, $familyId)
    {
        return $query->where('family_id', $familyId);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('subject_type', $type);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->latest()->limit($limit);
    }

    // ===================== HELPER METHODS =====================
    
    public function getSubjectTypeTextAttribute()
    {
        $types = [
            'family' => 'Keluarga',
            'member' => 'Anggota',
            'login' => 'Login',
            'logout' => 'Logout'
        ];
        
        return $types[$this->subject_type] ?? ucfirst($this->subject_type);
    }
}
