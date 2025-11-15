@extends('layouts.app-admin')

@section('title', 'Dashboard Admin')
@section('page_title', 'Tableau de bord Admin')

@section('content')
<div class="space-y-8">

  {{-- KPIs entités --}}
  <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Clients</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-clients">{{ $totalClients ?? 0 }}</div>
    </div>
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Tontines</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-tontines">{{ $totalTontines ?? 0 }}</div>
    </div>
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Collectes</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-collectes">{{ $totalCollectes ?? 0 }}</div>
    </div>
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Payouts</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-payouts">{{ $totalPayouts ?? 0 }}</div>
    </div>
  </div>

  {{-- KPIs montants globaux --}}
  <div class="grid gap-4 sm:grid-cols-4">
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Montants entrés (Total)</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-in-total">{{ number_format($amountInTotal ?? 0,2) }} XAF</div>
    </div>
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Montants sortis (Total)</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-out-total">{{ number_format($amountOutTotal ?? 0,2) }} XAF</div>
    </div>
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Solde net (Total)</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-net-total">{{ number_format($netTotal ?? 0,2) }} XAF</div>
    </div>
    <div class="bg-white border rounded-xl p-5">
      <div class="text-xs text-gray-500">Commissions (Total)</div>
      <div class="mt-2 text-2xl font-semibold text-gray-900" id="kpi-comm-total">{{ number_format($commissionTotal ?? 0,2) }} XAF</div>
    </div>
  </div>

  {{-- Périodes: jour / semaine / mois / année --}}
  <div class="grid gap-4 sm:grid-cols-4">
    @php $p=$periods ?? []; @endphp
    <div class="bg-white border rounded-xl p-4">
      <div class="text-xs text-gray-500">Aujourd’hui</div>
      <div class="mt-2 text-sm text-gray-700">Entrées: <span id="pi-in">{{ number_format($p['today']['in'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Sorties: <span id="pi-out">{{ number_format($p['today']['out'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Commissions: <span id="pi-comm">{{ number_format($p['today']['comm'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm font-medium text-gray-900">Net: <span id="pi-net">{{ number_format($p['today']['net'] ?? 0,2) }}</span> XAF</div>
    </div>
    <div class="bg-white border rounded-xl p-4">
      <div class="text-xs text-gray-500">Semaine</div>
      <div class="mt-2 text-sm text-gray-700">Entrées: <span id="pw-in">{{ number_format($p['week']['in'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Sorties: <span id="pw-out">{{ number_format($p['week']['out'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Commissions: <span id="pw-comm">{{ number_format($p['week']['comm'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm font-medium text-gray-900">Net: <span id="pw-net">{{ number_format($p['week']['net'] ?? 0,2) }}</span> XAF</div>
    </div>
    <div class="bg-white border rounded-xl p-4">
      <div class="text-xs text-gray-500">Mois</div>
      <div class="mt-2 text-sm text-gray-700">Entrées: <span id="pm-in">{{ number_format($p['month']['in'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Sorties: <span id="pm-out">{{ number_format($p['month']['out'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Commissions: <span id="pm-comm">{{ number_format($p['month']['comm'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm font-medium text-gray-900">Net: <span id="pm-net">{{ number_format($p['month']['net'] ?? 0,2) }}</span> XAF</div>
    </div>
    <div class="bg-white border rounded-xl p-4">
      <div class="text-xs text-gray-500">Année</div>
      <div class="mt-2 text-sm text-gray-700">Entrées: <span id="py-in">{{ number_format($p['year']['in'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Sorties: <span id="py-out">{{ number_format($p['year']['out'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm text-gray-700">Commissions: <span id="py-comm">{{ number_format($p['year']['comm'] ?? 0,2) }}</span> XAF</div>
      <div class="text-sm font-medium text-gray-900">Net: <span id="py-net">{{ number_format($p['year']['net'] ?? 0,2) }}</span> XAF</div>
    </div>
  </div>

  {{-- Filtre période (Entrées, Sorties, Net, Commissions) --}}
  <div class="bg-white border rounded-xl p-5">
    <div class="flex items-end gap-4 flex-wrap">
      <div>
        <label class="text-xs text-gray-500 mb-1 block">Du</label>
        <input type="date" id="f-date-from" class="mb-input">
      </div>
      <div>
        <label class="text-xs text-gray-500 mb-1 block">Au</label>
        <input type="date" id="f-date-to" class="mb-input">
      </div>
      <div class="flex gap-2">
        <button id="f-apply" class="mb-btn-primary px-3 py-2 rounded">Appliquer</button>
        <button id="f-reset" class="mb-btn-secondary px-3 py-2 rounded">Réinitialiser</button>
      </div>
    </div>
    <div class="mt-4 grid gap-3 sm:grid-cols-4">
      <div class="bg-gray-50 border rounded-lg p-3">
        <div class="text-xs text-gray-500">Entrées (filtre)</div>
        <div class="mt-1 text-xl font-semibold" id="kf-in">{{ number_format($filterTotals['in'] ?? 0,2) }} XAF</div>
      </div>
      <div class="bg-gray-50 border rounded-lg p-3">
        <div class="text-xs text-gray-500">Sorties (filtre)</div>
        <div class="mt-1 text-xl font-semibold" id="kf-out">{{ number_format($filterTotals['out'] ?? 0,2) }} XAF</div>
      </div>
      <div class="bg-gray-50 border rounded-lg p-3">
        <div class="text-xs text-gray-500">Commissions (filtre)</div>
        <div class="mt-1 text-xl font-semibold" id="kf-comm">{{ number_format($filterTotals['comm'] ?? 0,2) }} XAF</div>
      </div>
      <div class="bg-gray-50 border rounded-lg p-3">
        <div class="text-xs text-gray-500">Net (filtre)</div>
        <div class="mt-1 text-xl font-semibold" id="kf-net">{{ number_format($filterTotals['net'] ?? 0,2) }} XAF</div>
      </div>
    </div>
  </div>

  {{-- Graphiques --}}
  <div class="grid gap-6 lg:grid-cols-3">
    <div class="bg-white border rounded-xl p-6 lg:col-span-2">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-800">Entrées vs Sorties — 30 derniers jours</h2>
      </div>
      <div class="h-64"><canvas id="chartDailyIO"></canvas></div>
    </div>
    <div class="bg-white border rounded-xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-800">Statuts des tontines</h2>
      </div>
      <div class="h-64"><canvas id="chartStatus"></canvas></div>
    </div>
  </div>

  {{-- Commissions (30 derniers jours) --}}
  <div class="grid gap-6">
    <div class="bg-white border rounded-xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-800">Commissions — 30 derniers jours</h2>
      </div>
      <div class="h-64"><canvas id="chartCommissions"></canvas></div>
    </div>
  </div>

  {{-- Performances agents --}}
  <div class="grid gap-6 md:grid-cols-2">
    <div class="bg-white border rounded-xl p-6">
      <h2 class="text-sm font-semibold text-gray-800 mb-4">Top Agents — Aujourd’hui</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-4 py-2 text-left">Agent</th>
              <th class="px-4 py-2 text-left">Collectes</th>
              <th class="px-4 py-2 text-left">Montant</th>
            </tr>
          </thead>
          <tbody id="tbl-agents-today" class="divide-y divide-gray-100">
            @foreach(($agentsToday ?? []) as $a)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $a['label'] }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $a['ops'] }}</td>
                <td class="px-4 py-2 font-medium">{{ number_format($a['amount'],2) }} XAF</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
    <div class="bg-white border rounded-xl p-6">
      <h2 class="text-sm font-semibold text-gray-800 mb-4">Top Agents — Mois</h2>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-4 py-2 text-left">Agent</th>
              <th class="px-4 py-2 text-left">Collectes</th>
              <th class="px-4 py-2 text-left">Montant</th>
            </tr>
          </thead>
          <tbody id="tbl-agents-month" class="divide-y divide-gray-100">
            @foreach(($agentsMonth ?? []) as $a)
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $a['label'] }}</td>
                <td class="px-4 py-2 text-gray-700">{{ $a['ops'] }}</td>
                <td class="px-4 py-2 font-medium">{{ number_format($a['amount'],2) }} XAF</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@php
  // Evite l'opérateur ?? dans @json (source du ParseError)
  $statusCounts = isset($statusCounts) && is_array($statusCounts)
    ? $statusCounts
    : ['draft'=>0,'active'=>0,'completed'=>0,'paid'=>0,'archived'=>0,'cancelled'=>0];
@endphp
<script>
(function(){
  const statsUrl = "{{ route('admin.dashboard.stats') }}";

  const dailyLabels = @json($dailyLabels ?? []);
  const dailyIn     = @json($dailyIn ?? []);
  const dailyOut    = @json($dailyOut ?? []);
  const monthlyLabels = @json($monthlyLabels ?? []);
  const monthlyIn     = @json($monthlyIn ?? []);
  const monthlyOut    = @json($monthlyOut ?? []);

  const commLabels  = @json($commLabels ?? []);
  const commValues  = @json($commValues ?? []);
  const statusCounts = @json($statusCounts);

  const cd = document.getElementById('chartDailyIO');
  const cm = document.getElementById('chartMonthlyIO');
  const cc = document.getElementById('chartCommissions')
  const cs = document.getElementById('chartStatus');

  let chartDaily = null, chartMonthly = null, chartCom = null, chartStatus = null;

  if (cd){
    chartDaily = new Chart(cd, {
      type: 'line',
      data: {
        labels: dailyLabels,
        datasets: [
          { label:'Entrées', data: dailyIn, borderColor:'#16a34a', backgroundColor:'rgba(22,163,74,.15)', tension:.35, fill:true, pointRadius:2 },
          { label:'Sorties', data: dailyOut, borderColor:'#dc2626', backgroundColor:'rgba(220,38,38,.12)', tension:.35, fill:true, pointRadius:2 }
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, interaction:{mode:'index',intersect:false}, plugins:{ legend:{position:'bottom'} } }
    });
  }

  if (cm){
    chartMonthly = new Chart(cm, {
      type: 'bar',
      data: {
        labels: monthlyLabels,
        datasets: [
          { label:'Entrées', data: monthlyIn, backgroundColor:'rgba(22,163,74,.7)', borderRadius:6, maxBarThickness:38 },
          { label:'Sorties', data: monthlyOut, backgroundColor:'rgba(220,38,38,.7)', borderRadius:6, maxBarThickness:38 }
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'bottom'} }, scales:{ x:{ stacked:false }, y:{ beginAtZero:true } } }
    });
  }

  if (cc){
    chartCom = new Chart(cc, {
      type: 'line',
      data: {
        labels: commLabels,
        datasets: [
          { label:'Commissions', data: commValues, borderColor:'#7c3aed', backgroundColor:'rgba(124,58,237,.12)', tension:.35, fill:true, pointRadius:2 }
        ]
      },
      options: { responsive:true, maintainAspectRatio:false, plugins:{ legend:{position:'bottom'} }, scales:{ y:{ beginAtZero:true } } }
    });
  }

  if (cs){
    const order = ['draft','active','completed','paid','archived','cancelled'];
    const data = order.map(k => (statusCounts?.[k] || 0));
    chartStatus = new Chart(cs, {
      type: 'doughnut',
      data: {
        labels: ['Brouillon','Actif','Terminé','Payé','Archivé','Annulé'],
        datasets: [{
          data,
          backgroundColor: ['#94a3b8','#22c55e','#3b82f6','#14b8a6','#a78bfa','#f97316'],
          borderColor:'#fff', borderWidth:2, hoverOffset:6
        }]
      },
      options: { cutout:'55%', responsive:true, plugins:{ legend:{ position:'bottom' } } }
    });
  }

  function nf(x){ try{ return new Intl.NumberFormat('fr-FR',{minimumFractionDigits:2,maximumFractionDigits:2}).format(x||0); }catch(e){ return Number(x||0).toFixed(2); } }

  function renderAgents(tbodyId, rows){
    const tb = document.getElementById(tbodyId);
    if (!tb) return;
    tb.innerHTML = (rows||[]).map(r => `
      <tr class="hover:bg-gray-50">
        <td class="px-4 py-2">${r.label}</td>
        <td class="px-4 py-2 text-gray-700">${r.ops}</td>
        <td class="px-4 py-2 font-medium">${nf(r.amount)} XAF</td>
      </tr>
    `).join('');
  }

  async function refresh(extraParams = {}){
    try{
      const url = new URL(statsUrl, window.location.origin);
      Object.entries(extraParams).forEach(([k,v]) => { if (v) url.searchParams.set(k, v); });
      const res = await fetch(url.toString(), { headers: { 'Accept':'application/json' } });
      if(!res.ok) return;
      const d = await res.json();

      // KPIs entités
      const setText = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
      setText('kpi-clients', d.totalClients ?? 0);
      setText('kpi-tontines', d.totalTontines ?? 0);
      setText('kpi-collectes', d.totalCollectes ?? 0);
      setText('kpi-payouts', d.totalPayouts ?? 0);

      setText('kpi-in-total', nf(d.amountInTotal)+' XAF');
      setText('kpi-out-total', nf(d.amountOutTotal)+' XAF');
      setText('kpi-net-total', nf(d.netTotal)+' XAF');
      setText('kpi-comm-total', nf(d.commissionTotal)+' XAF');

      // Périodes
      if (d.periods){
        setText('pi-in', nf(d.periods.today?.in));
        setText('pi-out', nf(d.periods.today?.out));
        setText('pi-comm', nf(d.periods.today?.comm));
        setText('pi-net', nf(d.periods.today?.net));
        setText('pw-in', nf(d.periods.week?.in));
        setText('pw-out', nf(d.periods.week?.out));
        setText('pw-comm', nf(d.periods.week?.comm));
        setText('pw-net', nf(d.periods.week?.net));
        setText('pm-in', nf(d.periods.month?.in));
        setText('pm-out', nf(d.periods.month?.out));
        setText('pm-comm', nf(d.periods.month?.comm));
        setText('pm-net', nf(d.periods.month?.net));
        setText('py-in', nf(d.periods.year?.in));
        setText('py-out', nf(d.periods.year?.out));
        setText('py-comm', nf(d.periods.year?.comm));
        setText('py-net', nf(d.periods.year?.net));
      }

      // Charts IO
      if (chartDaily && d.dailyLabels && d.dailyIn && d.dailyOut){
        chartDaily.data.labels = d.dailyLabels;
        chartDaily.data.datasets[0].data = d.dailyIn;
        chartDaily.data.datasets[1].data = d.dailyOut;
        chartDaily.update('active');
      }
      if (chartMonthly && d.monthlyLabels && d.monthlyIn && d.monthlyOut){
        chartMonthly.data.labels = d.monthlyLabels;
        chartMonthly.data.datasets[0].data = d.monthlyIn;
        chartMonthly.data.datasets[1].data = d.monthlyOut;
        chartMonthly.update('active');
      }

      // Chart Commissions
      if (chartCom && d.commLabels && d.commValues){
        chartCom.data.labels = d.commLabels;
        chartCom.data.datasets[0].data = d.commValues;
        chartCom.update('active');
      }

      // Donut statuts
      if (chartStatus && d.statusCounts){
        const order = ['draft','active','completed','paid','archived','cancelled'];
        chartStatus.data.datasets[0].data = order.map(k => d.statusCounts?.[k] || 0);
        chartStatus.update('active');
      }

      // Agents
      renderAgents('tbl-agents-today', d.agentsToday || []);
      renderAgents('tbl-agents-month', d.agentsMonth || []);

      // Mises à jour filtre période (si présent)
      if (d.filterTotals){
        setText('kf-in',   nf(d.filterTotals.in)+' XAF');
        setText('kf-out',  nf(d.filterTotals.out)+' XAF');
        setText('kf-comm', nf(d.filterTotals.comm)+' XAF');
        setText('kf-net',  nf(d.filterTotals.net)+' XAF');
      }
    }catch(e){}
  }

  // Filtre période
  const fApply = document.getElementById('f-apply');
  const fReset = document.getElementById('f-reset');
  const fFrom  = document.getElementById('f-date-from');
  const fTo    = document.getElementById('f-date-to');

  if (fApply) fApply.addEventListener('click', ev => {
    ev.preventDefault();
    refresh({ date_from: fFrom?.value || '', date_to: fTo?.value || '' });
  });
  if (fReset) fReset.addEventListener('click', ev => {
    ev.preventDefault();
    if (fFrom) fFrom.value = '';
    if (fTo)   fTo.value   = '';
    refresh({});
  });

  refresh();
  setInterval(()=>refresh({}), 15000);
})();
</script>
@endsection
