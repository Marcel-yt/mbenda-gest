<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collecte;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Schema;

class CollecteController extends Controller
{
    public function index(Request $request)
    {
        $qClient  = trim($request->get('q_client', ''));
        $qAgent   = trim($request->get('q_agent', ''));
        $dateFrom = $request->get('date_from', '');
        $dateTo   = $request->get('date_to', '');

        $query = Collecte::query()
            ->with(['tontine.client', 'user', 'agent'])
            ->orderByDesc('id');

        // Filtre client (nom/prénom/phone [+ email si existe])
        if ($qClient !== '') {
            $query->whereHas('tontine.client', function ($c) use ($qClient) {
                $c->where(function ($w) use ($qClient) {
                    $w->where('first_name', 'like', "%{$qClient}%")
                      ->orWhere('last_name', 'like', "%{$qClient}%");
                    if (Schema::hasColumn('clients', 'phone')) {
                        $w->orWhere('phone', 'like', "%{$qClient}%");
                    }
                    if (Schema::hasColumn('clients', 'telephone')) {
                        $w->orWhere('telephone', 'like', "%{$qClient}%");
                    }
                    if (Schema::hasColumn('clients', 'email')) {
                        $w->orWhere('email', 'like', "%{$qClient}%");
                    }
                });
            });
        }

        // Filtre agent (relation user ou agent)
        if ($qAgent !== '') {
            $query->where(function ($qq) use ($qAgent) {
                $qq->whereHas('user', function ($u) use ($qAgent) {
                    $u->where(function ($w) use ($qAgent) {
                        $w->where('first_name', 'like', "%{$qAgent}%")
                          ->orWhere('last_name', 'like', "%{$qAgent}%");
                        if (Schema::hasColumn('users', 'name')) {
                            $w->orWhere('name', 'like', "%{$qAgent}%");
                        }
                        if (Schema::hasColumn('users', 'email')) {
                            $w->orWhere('email', 'like', "%{$qAgent}%");
                        }
                    });
                })
                ->orWhereHas('agent', function ($u) use ($qAgent) {
                    $u->where(function ($w) use ($qAgent) {
                        $w->where('first_name', 'like', "%{$qAgent}%")
                          ->orWhere('last_name', 'like', "%{$qAgent}%");
                        if (Schema::hasColumn('users', 'name')) {
                            $w->orWhere('name', 'like', "%{$qAgent}%");
                        }
                        if (Schema::hasColumn('users', 'email')) {
                            $w->orWhere('email', 'like', "%{$qAgent}%");
                        }
                    });
                });
            });
        }

        // Filtre par dates (collected_at si présent, sinon created_at)
        $dateColumn = Schema::hasColumn('collectes', 'collected_at') ? 'collected_at' : 'created_at';
        $from = null; $to = null;
        try { if ($dateFrom) $from = Carbon::createFromFormat('Y-m-d', $dateFrom)->startOfDay(); } catch (\Throwable $e) { $dateFrom = ''; }
        try { if ($dateTo)   $to   = Carbon::createFromFormat('Y-m-d', $dateTo)->endOfDay();   } catch (\Throwable $e) { $dateTo = ''; }

        if ($from && $to) {
            $query->whereBetween($dateColumn, [$from, $to]);
        } elseif ($from) {
            $query->where($dateColumn, '>=', $from);
        } elseif ($to) {
            $query->where($dateColumn, '<=', $to);
        }

        $collectes = $query->paginate(20)->appends($request->query());

        return view('pages.app.admin.collectes.index', compact('collectes', 'qClient', 'qAgent', 'dateFrom', 'dateTo'));
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