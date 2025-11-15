<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collecte extends Model
{
    use SoftDeletes;

    protected $table = 'collectes';

    protected $fillable = [
        'tontine_id',
        'client_id',
        'agent_id',
        'notes',
        'for_date',
    ];

    protected $casts = [
        'for_date' => 'date',
    ];

    // Montant dérivé de la tontine
    public function getAmountAttribute()
    {
        return $this->tontine?->daily_amount ?? null;
    }

    // Alias user (si ancien code attend user)
    public function user()
    {
        return $this->agent();
    }

    // Relation agent (corrigée, suppression du doublon ligne 48)
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function tontine()
    {
        return $this->belongsTo(\App\Models\Tontine::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getComputedAmountAttribute(): float
    {
        return (float) ($this->tontine?->daily_amount ?? 0);
    }
}