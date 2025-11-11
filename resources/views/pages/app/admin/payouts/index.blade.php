@extends('layouts.app-admin')

@section('title','Payouts')
@section('content')
<div class="bg-white border rounded-xl shadow-sm overflow-hidden">
  <div class="p-4">
    <div class="text-sm font-medium">Liste des paiements</div>
  </div>
  <div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200 text-sm">
      <thead class="bg-gray-50 text-xs font-semibold text-gray-600">
        <tr>
          <th class="px-4 py-3 text-left">ID</th>
          <th class="px-4 py-3 text-left">Tontine</th>
          <th class="px-4 py-3 text-left">Client</th>
          <th class="px-4 py-3 text-left">Net</th>
          <th class="px-4 py-3 text-left">Admin</th>
          <th class="px-4 py-3 text-left">Date / Heure</th>
          <th class="px-4 py-3 text-right">Actions</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-100">
        @forelse($payouts as $p)
          @php $currency = $p->tontine->settings['currency'] ?? 'XAF'; @endphp
          <tr class="hover:bg-gray-50">
            <td class="px-4 py-3">{{ $p->id }}</td>
            <td class="px-4 py-3">{{ $p->tontine?->code ?? '-' }}</td>
            <td class="px-4 py-3">{{ trim(($p->client?->first_name ?? '').' '.($p->client?->last_name ?? '')) ?: '-' }}</td>
            <td class="px-4 py-3">{{ number_format($p->amount_net,2) }} {{ $currency }}</td>
            <td class="px-4 py-3">{{ $p->admin?->email ?? '-' }}</td>
            <td class="px-4 py-3">{{ optional($p->paid_at)->format('d/m/Y H:i') }}</td>
            <td class="px-4 py-3 text-right">
              <a href="{{ route('admin.payouts.show',$p->id) }}"
                 class="inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-medium
                        bg-[var(--mb-primary)] text-white shadow-sm hover:bg-[var(--mb-primary)] focus:outline-none
                        focus:ring-2 focus:ring-indigo-400 focus:ring-offset-1 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 5c-7 0-9 7-9 7s2 7 9 7 9-7 9-7-2-7-9-7zm0 4a3 3 0 100 6 3 3 0 000-6z" />
                </svg>
                <span>Détails</span>
              </a>
              @if($p->receipt_path)
                <a href="{{ asset('storage/'.$p->receipt_path) }}" target="_blank"
                   class="ml-2 inline-flex items-center gap-2 px-3 py-1.5 rounded-md text-xs font-medium
                          bg-emerald-50 text-emerald-700 border border-emerald-200 hover:bg-emerald-100
                          focus:outline-none focus:ring-2 focus:ring-emerald-300 focus:ring-offset-1 transition">
                  <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-7 8h8a2 2 0 002-2V6a2 2 0 00-2-2h-5.586a2 2 0 00-1.414.586l-3.414 3.414A2 2 0 005 9.414V18a2 2 0 002 2z" />
                  </svg>
                  <span>Reçu PDF</span>
                </a>
              @else
                <span class="ml-2 inline-flex items-center px-3 py-1.5 rounded-md text-xs font-medium
                             bg-gray-100 text-gray-400 border border-gray-200 cursor-not-allowed">
                  Reçu indisponible
                </span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-4 py-8 text-center text-gray-500">Aucun paiement</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  <div class="p-4">{{ $payouts->links() }}</div>
</div>
@endsection