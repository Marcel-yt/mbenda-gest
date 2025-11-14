@extends('layouts.app-admin')

@section('title','Payouts')
@section('content')

{{-- Filtres (une ligne, client + plage de paiement) --}}
<form id="payout-filters" method="GET" action="{{ route('admin.payouts.index') }}" class="mb-3">
  <div class="bg-white border rounded-xl p-3 flex items-end gap-4 flex-nowrap overflow-x-auto">
    <div class="shrink-0" style="width:460px;">
      <label class="text-xs text-gray-500 mb-1 block">Client / Code</label>
      <input type="text" name="q_client" value="{{ request('q_client',$qClient ?? '') }}"
             class="mb-input" placeholder="Nom, prénom, tél, email, code" autocomplete="off">
    </div>
    <div class="shrink-0" style="width:160px;">
      <label class="text-xs text-gray-500 mb-1 block">Payé du</label>
      <input type="date" name="paid_from" value="{{ request('paid_from',$paidFrom ?? '') }}" class="mb-input">
    </div>
    <div class="shrink-0" style="width:160px;">
      <label class="text-xs text-gray-500 mb-1 block">Au</label>
      <input type="date" name="paid_to" value="{{ request('paid_to',$paidTo ?? '') }}" class="mb-input">
    </div>
    <div class="shrink-0" style="width:140px;">
      <label class="text-xs text-transparent mb-1 block">Reset</label>
      <button type="button" id="reset-dates"
              class="w-full inline-flex justify-center items-center px-3 py-3 rounded-md border border-[var(--mb-primary)] bg-white text-xs text-[var(--mb-primary)] hover:bg-gray-200">
        Réinitialiser
      </button>
    </div>
  </div>
</form>

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
  <div class="p-4">{{ $payouts->appends(request()->query())->links() }}</div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const form = document.getElementById('payout-filters');
  if (!form) return;
  const submit = () => form.submit();

  const qc = form.querySelector('input[name="q_client"]');
  if (qc) {
    qc.setAttribute('autocomplete','off');
    qc.addEventListener('input', submit);
    qc.addEventListener('keydown', e => { if (e.key === 'Escape') { qc.value=''; submit(); }});
  }
  ['paid_from','paid_to'].forEach(n=>{
    const el = form.querySelector(`[name="${n}"]`);
    if (el) el.addEventListener('change', submit);
  });

  const reset = document.getElementById('reset-dates');
  if (reset) {
    reset.addEventListener('click', () => {
      const f = form.querySelector('input[name="paid_from"]');
      const t = form.querySelector('input[name="paid_to"]');
      if (f) f.value='';
      if (t) t.value='';
      submit();
    });
  }
});
</script>
@endsection