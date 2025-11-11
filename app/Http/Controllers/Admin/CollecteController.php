<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use Illuminate\Http\Request;

class CollecteController extends Controller
{
    public function index()
    {
        $collectes = Collecte::with(['tontine.client','user']) // charge aussi le client de la tontine
            ->latest('id')
            ->paginate(20);

        return view('pages.app.admin.collectes.index', compact('collectes'));
    }

    public function show(int $id)
    {
        $collecte = Collecte::with(['tontine.client','user','agent'])->findOrFail($id);

        return view('pages.app.admin.collectes.show', compact('collecte'));
    }

    public function edit(int $id)
    {
        $collecte = Collecte::with(['tontine','user'])->findOrFail($id);

        return view('pages.app.admin.collectes.edit', compact('collecte'));
    }

    // Décommente si tu actives l’édition
    // public function update(Request $request, int $id)
    // {
    //     $collecte = Collecte::findOrFail($id);
    //     $data = $request->validate([
    //         'amount' => 'required|numeric|min:0',
    //         'collected_at' => 'nullable|date',
    //     ]);
    //     $collecte->amount = $data['amount'];
    //     if (!empty($data['collected_at'])) {
    //         $collecte->collected_at = $data['collected_at'];
    //     }
    //     $collecte->save();
    //     return redirect()->route('admin.collectes.show', $collecte->id)->with('success','Collecte mise à jour.');
    // }
}