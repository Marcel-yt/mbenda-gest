<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'created_by_agent_id',
        'first_name',
        'last_name',
        'indicatif',
        'phone',
        'registered_at',
        'address',
        'notes',
        'photo_profil',
        'statut',
    ];

    protected $casts = [
        'registered_at' => 'date',
        'statut' => 'boolean',
    ];

    public function creatorAgent()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by_agent_id');
    }

    public function tontines()
    {
        return $this->hasMany(\App\Models\Tontine::class);
    }
}