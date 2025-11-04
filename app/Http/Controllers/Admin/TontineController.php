<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Tontine;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class TontineController extends Controller
{
    // Note: protège les routes via middleware dans routes/web.php (auth + role:admin)

    public function index(): View
    {
        $tontines = Tontine::with('client','creator')->orderByDesc('created_at')->paginate(20);
        return view('pages.app.admin.tontines.index', compact('tontines'));
    }

    public function create(): View
    {
        return view('pages.app.admin.tontines.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'daily_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'duration_days' => 'nullable|integer|min:1',
            'allow_early_payout' => 'boolean',
            'commission_days' => 'nullable|integer|min:0',
        ]);

        $data['created_by_agent_id'] = $request->user()->id;
        $tontine = Tontine::create($data);

        return redirect()->route('admin.tontines.show', $tontine)->with('success', 'Tontine créée.');
    }

    public function show(Tontine $tontine): View
    {
        // load only relations that exist in the code / DB
        $tontine->load('client','creator');
        return view('pages.app.admin.tontines.show', compact('tontine'));
    }

    public function edit(Tontine $tontine): View
    {
        return view('pages.app.admin.tontines.edit', compact('tontine'));
    }

    public function update(Request $request, Tontine $tontine): RedirectResponse
    {
        $data = $request->validate([
            'daily_amount' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'duration_days' => 'nullable|integer|min:1',
            'allow_early_payout' => 'boolean',
            'commission_days' => 'nullable|integer|min:0',
        ]);

        $tontine->update($data);

        return back()->with('success', 'Tontine mise à jour.');
    }

    public function destroy(Tontine $tontine): RedirectResponse
    {
        $tontine->delete();
        return redirect()->route('admin.tontines.index')->with('success', 'Tontine supprimée (soft).');
    }

    // simple finalize action (admin triggers payout flow)
    public function finalize(Request $request, Tontine $tontine): RedirectResponse
    {
        // here you would run the payout calculation (sum collectes, apply commission_days etc.)
        // set status completed -> paid/archived in real flow after payout processing
        $tontine->status = 'completed';
        $tontine->completed_at = now();
        $tontine->save();

        return redirect()->route('admin.tontines.show', $tontine)->with('success', 'Tontine marquée comme clôturée (completed).');
    }
}