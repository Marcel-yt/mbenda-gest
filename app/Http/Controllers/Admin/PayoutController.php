<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\Tontine;
use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PayoutController extends Controller
{
    public function index(Request $request)
    {
        $qClient   = trim($request->get('q_client',''));
        $paidFrom  = $request->get('paid_from','');
        $paidTo    = $request->get('paid_to','');

        $query = Payout::with(['client','tontine','admin'])->orderByDesc('id');

        if ($qClient !== '') {
            $query->where(function($w) use ($qClient) {
                $w->whereHas('client', function($c) use ($qClient) {
                    $c->where('first_name','like',"%{$qClient}%")
                      ->orWhere('last_name','like',"%{$qClient}%")
                      ->orWhere('phone','like',"%{$qClient}%");
                    if (\Schema::hasColumn('clients','email')) {
                        $c->orWhere('email','like',"%{$qClient}%");
                    }
                })
                ->orWhereHas('tontine', function($t) use ($qClient) {
                    $t->where('code','like',"%{$qClient}%");
                });
            });
        }

        $from = $to = null;
        try { if ($paidFrom) $from = Carbon::createFromFormat('Y-m-d',$paidFrom)->startOfDay(); } catch(\Throwable $e){ $paidFrom=''; }
        try { if ($paidTo)   $to   = Carbon::createFromFormat('Y-m-d',$paidTo)->endOfDay();   } catch(\Throwable $e){ $paidTo=''; }

        if ($from && $to) {
            $query->whereBetween('paid_at', [$from,$to]);
        } elseif ($from) {
            $query->where('paid_at','>=',$from);
        } elseif ($to) {
            $query->where('paid_at','<=',$to);
        }

        $payouts = $query->paginate(20)->appends($request->query());

        return view('pages.app.admin.payouts.index', compact('payouts','qClient','paidFrom','paidTo'));
    }

    public function create(?int $tontine = null)
    {
        $selected = null;
        $summary = null;

        if ($tontine) {
            $selected = Tontine::with('client')->findOrFail($tontine);

            if (Payout::where('tontine_id', $selected->id)->exists()) {
                return redirect()->route('admin.payouts.index')->with('error','Paiement déjà enregistré pour cette tontine.');
            }

            $collectesQuery = Collecte::where('tontine_id', $selected->id);
            $daysCollected = $collectesQuery->when(
                \Schema::hasColumn('collectes','for_date'),
                fn($q) => $q->distinct('for_date')->count('for_date'),
                fn()   => $collectesQuery->count()
            );
            $effectiveDays = min($daysCollected, (int)($selected->duration_days ?? 0));
            $daily = (float)($selected->daily_amount ?? 0);
            $amount_gross = round($daily * max(0, $effectiveDays), 2);
            $commission_amount = round($daily, 2);
            $amount_net = round(max(0, $amount_gross - $commission_amount), 2);
            $currency = $selected->settings['currency'] ?? 'XAF';

            $summary = compact('daysCollected','effectiveDays','daily','amount_gross','commission_amount','amount_net','currency');
        }

        return view('pages.app.admin.payouts.create', compact('selected','summary'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'tontine_id' => 'required|exists:tontines,id',
            'notes'      => 'nullable|string|max:2000',
        ]);

        $tontine = Tontine::with('client')->findOrFail($data['tontine_id']);

        if (Payout::where('tontine_id', $tontine->id)->exists()) {
            return back()->withErrors(['tontine_id' => 'Paiement déjà existant.']);
        }

        $payout = DB::transaction(function () use ($tontine, $data) {
            $collectesQuery = Collecte::where('tontine_id', $tontine->id);
            $daysCollected = $collectesQuery->when(
                \Schema::hasColumn('collectes','for_date'),
                fn($q) => $q->distinct('for_date')->count('for_date'),
                fn()   => $collectesQuery->count()
            );

            $effectiveDays = min($daysCollected, (int)($tontine->duration_days ?? 0));
            $daily = (float)($tontine->daily_amount ?? 0);
            $amount_gross = round($daily * max(0, $effectiveDays), 2);
            $commission_amount = round($daily, 2);
            $amount_net = round(max(0, $amount_gross - $commission_amount), 2);

            $payout = Payout::create([
                'tontine_id'        => $tontine->id,
                'client_id'         => $tontine->client_id,
                'paid_by_admin_id'  => auth()->id(),
                'paid_at'           => now(),
                'amount_gross'      => $amount_gross,
                'commission_amount' => $commission_amount,
                'amount_net'        => $amount_net,
                'receipt_path'      => null,
                'notes'             => $data['notes'] ?? null,
            ]);

            $tontine->status = 'paid';
            $tontine->paid_at = now();
            $tontine->save();

            return $payout;
        });

        // Génération PDF hors transaction
        try {
            if (!extension_loaded('gd')) {
                Log::warning('GD non chargé: logo ignoré');
            }

            Storage::disk('public')->makeDirectory('payouts');

            $payout->load(['tontine.client','admin']);

            $pdf = app('dompdf.wrapper')
                ->loadView('pages.app.admin.payouts.receipt', [
                    'payout'   => $payout,
                    'skipLogo' => !extension_loaded('gd'),
                ])
                ->setPaper('A4');

            $relPath = 'payouts/receipt_'.$payout->id.'.pdf';
            Storage::disk('public')->put($relPath, $pdf->output());

            if (Storage::disk('public')->exists($relPath)) {
                $payout->update(['receipt_path' => $relPath]);
            } else {
                Log::error('Reçu non créé (fichier absent) payout '.$payout->id);
            }
        } catch (\Throwable $e) {
            Log::error('Erreur génération reçu payout '.$payout->id.' : '.$e->getMessage());
        }

        return redirect()->route('admin.payouts.show', $payout->id)
            ->with('success','Paiement confirmé.');
    }

    public function show(int $id)
    {
        $payout = Payout::with(['tontine.client','admin'])->findOrFail($id);
        return view('pages.app.admin.payouts.show', compact('payout'));
    }
}