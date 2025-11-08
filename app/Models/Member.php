<?php
// app/Models/Member.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'full_name',
        'profile_photo',
        'nickname',
        'birth_place',
        'birth_date',
        'occupation',
        'phone_number',
        'email',
        'gender',
        'status',
        'generation',
        'parent_id',
        'domicile_city',
        'domicile_province',
        'ktp_address',
        'current_address',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getNameAttribute($value)
    {
        // Jika ada field 'name', return value
        if ($value !== null) {
            return $value;
        }
        
        // Fallback ke field lain jika ada
        if (isset($this->attributes['full_name'])) {
            return $this->attributes['full_name'];
        }
        
        if (isset($this->attributes['first_name'])) {
            $firstName = $this->attributes['first_name'];
            $lastName = $this->attributes['last_name'] ?? '';
            return trim($firstName . ' ' . $lastName);
        }
        
        return 'Unknown Member';
    }


    public function family()
    {
        return $this->belongsTo(Family::class);
    }

    public function parent()
    {
        return $this->belongsTo(Member::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Member::class, 'parent_id');
    }

    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo 
            ? Storage::url($this->profile_photo)
            : 'https://ui-avatars.com/api/?name=' . urlencode($this->full_name);
    }

    protected static function booted()
    {
        static::created(function ($member) {
            ActivityLog::create([
                'family_id' => $member->family_id,
                'subject_type' => 'member',
                'subject_id' => $member->id,
                'description' => "Anggota baru '{$member->full_name}' ditambahkan",
            ]);
        });

        static::updated(function ($member) {
            $changes = $member->getChanges();
            unset($changes['updated_at']);
            
            if (!empty($changes)) {
                $changeList = [];
                foreach ($changes as $field => $newValue) {
                    $changeList[] = $field;
                }
                
                ActivityLog::create([
                    'family_id' => $member->family_id,
                    'subject_type' => 'member',
                    'subject_id' => $member->id,
                    'description' => "Anggota '{$member->full_name}' diubah: " . implode(', ', $changeList),
                ]);
            }
        });

        static::deleted(function ($member) {
            ActivityLog::create([
                'family_id' => $member->family_id,
                'subject_type' => 'member',
                'subject_id' => $member->id,
                'description' => "Anggota '{$member->full_name}' dihapus",
            ]);
        });
    }
}