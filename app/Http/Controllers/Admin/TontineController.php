<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tontine;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class TontineController extends Controller
{
    public function index(Request $request)
    {
        $q            = trim($request->get('q',''));
        $status       = $request->get('status','');
        $created_from = $request->get('created_from','');
        $created_to   = $request->get('created_to','');

        $allowed = ['draft','active','completed','paid','archived','cancelled'];

        $query = \App\Models\Tontine::with(['client','creator'])->orderByDesc('id');

        // Apply search filter (client name/phone or code)
        if ($q !== '') {
            $query->where(function ($s) use ($q) {
                $s->whereHas('client', function ($c) use ($q) {
                    $c->where('first_name','like',"%{$q}%")
                      ->orWhere('last_name','like',"%{$q}%")
                      ->orWhere('phone','like',"%{$q}%");
                    if (\Schema::hasColumn('clients','email')) {
                        $c->orWhere('email','like',"%{$q}%");
                    }
                })
                ->orWhere('code','like',"%{$q}%");
            });
        }

        // Save a base query that includes search/date filters but not the status filter
        $baseQuery = clone $query;

        // Date filters
        $from = $to = null;
        try { if ($created_from) $from = \Carbon\Carbon::createFromFormat('Y-m-d',$created_from)->startOfDay(); } catch (\Throwable $e) { $created_from=''; }
        try { if ($created_to)   $to   = \Carbon\Carbon::createFromFormat('Y-m-d',$created_to)->endOfDay();   } catch (\Throwable $e) { $created_to=''; }

        if ($from && $to)        $baseQuery->whereBetween('created_at',[$from,$to]);
        elseif ($from)           $baseQuery->where('created_at','>=',$from);
        elseif ($to)             $baseQuery->where('created_at','<=',$to);

        // Compute totals (respecting search & date filters but NOT pagination)
        $totalCount = (clone $baseQuery)->count();
        $activeCount = (clone $baseQuery)->where('status','active')->count();
        $completedCount = (clone $baseQuery)->where('status','completed')->count();
        $draftCount = (clone $baseQuery)->where('status','draft')->count();
        $paidCount = (clone $baseQuery)->where('status','paid')->count();
        $cancelledCount = (clone $baseQuery)->where('status','cancelled')->count();

        // Now apply status filter (if provided) to the main query used for pagination
        if ($status !== '' && in_array($status,$allowed,true)) {
            $query->where('status',$status);
        } else {
            $status = '';
        }
        $tontines = $query->paginate(20)->appends($request->query());

        return view('pages.app.admin.tontines.index', compact('tontines','q','status','created_from','created_to', 'totalCount','activeCount','completedCount','draftCount', 'paidCount', 'cancelledCount'));
    }

    public function show(int $id)
    {
        $tontine = Tontine::with(['client'])->findOrFail($id);

        return view('pages.app.admin.tontines.show', compact('tontine'));
    }

    public function edit(int $id)
    {
        $tontine = Tontine::with(['client'])->findOrFail($id);

        // Edition page removed — redirect to show with info
        return redirect()->route('admin.tontines.show', $tontine->id)
            ->with('info', 'La modification des tontines n\'est plus disponible via cette interface.');
    }

    public function update(Request $request, int $id)
    {
        $tontine = Tontine::findOrFail($id);

        // On ne modifie que daily_amount et start_date
        $data = $request->validate([
            'daily_amount' => 'required|numeric|min:0',
            'start_date'   => 'nullable|date',
        ]);

        // maj date de début
        $tontine->start_date = !empty($data['start_date']) ? Carbon::parse($data['start_date']) : $tontine->start_date;
        // recalcul de la fin prévue en fonction de la durée existante
        if ($tontine->start_date && $tontine->duration_days) {
            $tontine->expected_end_date = $tontine->start_date->copy()->addDays(max(1, (int)$tontine->duration_days) - 1);
        }

        $tontine->save();

        return redirect()
            ->route('admin.tontines.show', $tontine->id)
            ->with('success', 'Tontine mise à jour.');
    }

    public function pay(Request $request, int $id)
    {
        $tontine = Tontine::findOrFail($id);

        if (in_array($tontine->status, ['paid','cancelled','archived','draft'])) {
            return redirect()->route('admin.tontines.show', $tontine->id)
                ->with('error', 'Paiement non autorisé pour ce statut.');
        }

        if ($tontine->status === 'active' && !$request->boolean('force')) {
            return redirect()->route('admin.tontines.show', $tontine->id)
                ->with('error', 'Confirmation manquante.');
        }

        $tontine->status = 'paid';
        $tontine->paid_at = $tontine->paid_at ?: now();
        $tontine->save();

        return redirect()->route('admin.tontines.show', $tontine->id)
            ->with('success', 'Statut passé à paid.');
    }

    /**
     * Cancel (annuler) a tontine — only allowed when not paid.
     */
    public function cancel(Request $request, int $id)
    {
        $tontine = Tontine::findOrFail($id);

        if ($tontine->status === 'paid') {
            return redirect()->route('admin.tontines.show', $tontine->id)
                ->with('error', 'Impossible d\'annuler une tontine déjà payée.');
        }

        if ($tontine->status === 'cancelled') {
            return redirect()->route('admin.tontines.show', $tontine->id)
                ->with('info', 'La tontine est déjà annulée.');
        }

        $data = $request->validate([
            'cancel_reason' => 'nullable|string|max:1000',
        ]);

        $tontine->status = 'cancelled';
        $tontine->cancelled_by = $request->user()->id;
        $tontine->cancelled_reason = $data['cancel_reason'] ?? null;
        $tontine->cancelled_at = now();
        $tontine->save();

        return redirect()->route('admin.tontines.index')
            ->with('success', 'Tontine annulée avec succès. Les collectes associées ne seront plus prises en compte dans les calculs.');
    }
}