<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tontine;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class TontineController extends Controller
{
    public function index()
    {
        $tontines = Tontine::with(['client'])
            ->latest('id')
            ->paginate(20);

        return view('pages.app.admin.tontines.index', compact('tontines'));
    }

    public function show(int $id)
    {
        $tontine = Tontine::with(['client'])->findOrFail($id);

        return view('pages.app.admin.tontines.show', compact('tontine'));
    }

    public function edit(int $id)
    {
        $tontine = Tontine::with(['client'])->findOrFail($id);

        return view('pages.app.admin.tontines.edit', compact('tontine'));
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
}