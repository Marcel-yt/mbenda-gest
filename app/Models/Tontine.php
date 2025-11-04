<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class Tontine extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'code',
        'uuid',
        'client_id',
        'created_by_agent_id',
        'daily_amount',
        'duration_days',
        'start_date',
        'expected_end_date',
        'actual_end_date',
        'status',
        'allow_early_payout',
        'commission_days',
        'collected_total',
        'settings',
    ];

    protected $casts = [
        'settings' => 'array',
        'allow_early_payout' => 'boolean',
        'daily_amount' => 'decimal:2',
        'collected_total' => 'decimal:2',
        'start_date' => 'date',
        'expected_end_date' => 'date',
        'actual_end_date' => 'date',
    ];

    // relations
    public function client()
    {
        return $this->belongsTo(\App\Models\Client::class, 'client_id');
    }

    public function creator()
    {
        return $this->belongsTo(\App\Models\User::class, 'created_by_agent_id');
    }

    /*
     * Relations for collectes and payouts are commented out for now
     * to avoid "Class not found" errors until those models/tables exist.
     *
     * Uncomment or implement these when Collecte and TontinePayout models are added.
     *
     * public function collectes()
     * {
     *     return $this->hasMany(\App\Models\Collecte::class);
     * }
     *
     * public function payouts()
     * {
     *     return $this->hasMany(\App\Models\TontinePayout::class);
     * }
     */

    protected static function booted()
    {
        static::creating(function ($model) {
            // generate sequential code TONT000001 — simple approach
            if (empty($model->code)) {
                // use the Eloquent query so withTrashed() is available
                $maxId = self::withTrashed()->max('id') ?? 0;
                $next = $maxId + 1;
                $model->code = 'TONT' . str_pad($next, 6, '0', STR_PAD_LEFT);
            }

            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }

            // expected_end_date = start_date + (duration_days - 1)
            if (! empty($model->start_date) && empty($model->expected_end_date)) {
                $model->expected_end_date = Carbon::parse($model->start_date)
                    ->addDays(intval($model->duration_days ?: 31) - 1)
                    ->toDateString();
            }
        });

        static::updating(function ($model) {
            // recalc expected_end_date if start_date or duration changed
            if ($model->isDirty(['start_date','duration_days'])) {
                if (! empty($model->start_date) && ! empty($model->duration_days)) {
                    $model->expected_end_date = Carbon::parse($model->start_date)
                        ->addDays(intval($model->duration_days) - 1)
                        ->toDateString();
                }
            }
        });
    }
}