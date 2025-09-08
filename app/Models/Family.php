<?php
// app/Models/Family.php - UPDATE EXISTING FILE

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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

    // Helper methods
    public function getMemberCountAttribute()
    {
        return $this->members()->count();
    }

    public function getLatestActivityAttribute()
    {
        return $this->activityLogs()->latest()->first();
    }

    // Authentication methods
    public function getAuthIdentifierName()
    {
        return 'id';
    }

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}