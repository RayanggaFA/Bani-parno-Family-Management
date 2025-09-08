<?php
// app/Models/ActivityLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'family_id',
        'subject_type',
        'subject_id',
        'description',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

         // Scope untuk hari ini
    public function scopeToday(Builder $query)
    {
        return $query->whereDate('created_at', now()->toDateString());
    }

    // Scope untuk bulan ini
    public function scopeThisMonth(Builder $query)
    {
        return $query->whereYear('created_at', now()->year)
                     ->whereMonth('created_at', now()->month);
    }
    public function family()
    {
        return $this->belongsTo(Family::class);
    }
}