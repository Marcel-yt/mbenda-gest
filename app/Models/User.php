<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles; // ajouté

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles; // ajouté HasRoles

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'password',
        'role',
        'color_hex',
        'active',
        'last_login_at',
        'photo_profil',
        'is_super_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_login_at' => 'datetime',
        'active' => 'boolean',
        'password' => 'hashed',
        'is_super_admin' => 'boolean',
    ];

    // Accès pratique: {{ Auth::user()->name }}
    public function getNameAttribute(): string
    {
        return trim(($this->first_name ?? '').' '.($this->last_name ?? '')) ?: ($this->email ?? '');
    }

    public function isAdmin(): bool   { return $this->role === 'admin'; }
    public function isAgent(): bool   { return $this->role === 'agent'; }

    /**
     * L'utilisateur qui a créé cet utilisateur (optionnel).
     */
    public function creator()
    {
        return $this->belongsTo(self::class, 'created_by');
    }

    /**
     * Clients créés par cet agent.
     */
    public function clients()
    {
        return $this->hasMany(\App\Models\Client::class, 'created_by_agent_id');
    }
}
