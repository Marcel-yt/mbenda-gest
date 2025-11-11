<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payout extends Model
{
    protected $fillable = [
        'tontine_id',
        'client_id',
        'paid_by_admin_id',
        'paid_at',
        'amount_gross',
        'commission_amount',
        'amount_net',
        'receipt_path',
        'notes',
    ];

    protected $casts = [
        'paid_at' => 'datetime',
        'amount_gross' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'amount_net' => 'decimal:2',
    ];

    public function tontine() { return $this->belongsTo(Tontine::class); }
    public function client() { return $this->belongsTo(Client::class); }
    public function admin()  { return $this->belongsTo(User::class, 'paid_by_admin_id'); }
}