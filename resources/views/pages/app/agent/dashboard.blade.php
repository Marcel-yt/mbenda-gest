@extends('layouts.app-agent')

@section('title', 'Tableau de bord Agent')
@section('page_title', 'Tableau de bord Agent')

@section('content')
@php
  $todayAmount   = $todayAmount ?? 0;
  $activeTontines = $activeTontines ?? 0;
  $clientsCount   = $clientsCount ?? 0;
  $clientsCollectedTodayCount = $clientsCollectedTodayCount ?? 0;
  $clientDailyStats = collect($clientDailyStats ?? []);
  $dailyLabels   = $dailyLabels ?? [];
  $dailyValues   = $dailyValues ?? [];
  $statusCounts  = $statusCounts ?? ['draft'=>0,'active'=>0,'completed'=>0,'paid'=>0,'archived'=>0,'cancelled'=>0];
@endphp

<div class="space-y-8">

  {{-- KPIs simplifiés --}}
  <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
    <div class="bg-white border rounded-xl p-5 flex flex-col">
      <span class="text-xs uppercase tracking-wide text-gray-500">Collectes aujourd’hui</span>
      <span id="kpi-today-amount" class="mt-2 text-2xl font-semibold text-gray-900">{{ number_format($todayAmount, 2) }} XAF</span>
      <span class="mt-1 text-xs text-gray-500">Montant encaissé</span>
    </div>
    <div class="bg-white border rounded-xl p-5 flex flex-col">
      <span class="text-xs uppercase tracking-wide text-gray-500">Clients collectés aujourd’hui</span>
      <span id="kpi-clients-collected-today" class="mt-2 text-2xl font-semibold text-gray-900">{{ $clientsCollectedTodayCount }}</span>
      <span class="mt-1 text-xs text-gray-500">Distincts</span>
    </div>
    <div class="bg-white border rounded-xl p-5 flex flex-col">
      <span class="text-xs uppercase tracking-wide text-gray-500">Tontines actives</span>
      <span id="kpi-active-tontines" class="mt-2 text-2xl font-semibold text-gray-900">{{ $activeTontines }}</span>
      <span class="mt-1 text-xs text-gray-500">En cours</span>
    </div>
    <div class="bg-white border rounded-xl p-5 flex flex-col">
      <span class="text-xs uppercase tracking-wide text-gray-500">Clients créés</span>
      <span id="kpi-clients-count" class="mt-2 text-2xl font-semibold text-gray-900">{{ $clientsCount }}</span>
      <span class="mt-1 text-xs text-gray-500">Par vous</span>
    </div>
  </div>

  {{-- Courbe 30 jours + donut statuts --}}
  <div class="grid gap-6 lg:grid-cols-3">
    <div class="bg-white border rounded-xl p-6 lg:col-span-2">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-800">Collectes – 30 derniers jours</h2>
      </div>
      <div class="h-64"><canvas id="chartDaily"></canvas></div>
    </div>
    <div class="bg-white border rounded-xl p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="text-sm font-semibold text-gray-800">Collectes du jour — Conforme / Anticipé / Retardé</h2>
      </div>
      <div class="h-64"><canvas id="chartTiming"></canvas></div>
    </div>
  </div>

  {{-- Liste des clients collectés aujourd’hui --}}
  <div class="bg-white border rounded-xl p-6">
    <div class="flex items-center justify-between mb-4">
      <h2 class="text-sm font-semibold text-gray-800">Clients collectés aujourd’hui</h2>
      <span id="kpi-clients-collected-today-badge" class="text-xs text-gray-500">{{ $clientsCollectedTodayCount }} client(s)</span>
    </div>

    @if($clientDailyStats->isEmpty())
      <div id="clients-today-empty" class="py-8 text-center text-sm text-gray-500">Aucune collecte enregistrée aujourd’hui.</div>
      <div class="overflow-x-auto hidden" id="clients-today-table-wrap">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-4 py-2 text-left">Client</th>
              <th class="px-4 py-2 text-left">Adresse</th>
              <th class="px-4 py-2 text-left">Date</th>
              <th class="px-4 py-2 text-left">Heure</th>
              <th class="px-4 py-2 text-left">Montant total</th>
              <th class="px-4 py-2 text-left">Collectes</th>
            </tr>
          </thead>
          <tbody id="clients-today-body" class="divide-y divide-gray-100"></tbody>
        </table>
      </div>
    @else
      <div class="overflow-x-auto" id="clients-today-table-wrap">
        <table class="min-w-full text-sm">
          <thead class="bg-gray-50 text-gray-600 text-xs uppercase tracking-wide">
            <tr>
              <th class="px-4 py-2 text-left">Client</th>
              <th class="px-4 py-2 text-left">Adresse</th>
              <th class="px-4 py-2 text-left">Date</th>
              <th class="px-4 py-2 text-left">Heure</th>
              <th class="px-4 py-2 text-left">Montant total</th>
              <th class="px-4 py-2 text-left">Collectes</th>
            </tr>
          </thead>
          <tbody id="clients-today-body" class="divide-y divide-gray-100">
            @foreach($clientDailyStats as $row)
              @php
                $dt = $row['last_created_at'] ? \Carbon\Carbon::parse($row['last_created_at']) : null;
              @endphp
              <tr class="hover:bg-gray-50">
                <td class="px-4 py-2">{{ $row['name'] }}</td>
                <td class="px-4 py-2">{{ $row['address'] }}</td>
                <td class="px-4 py-2">{{ $dt ? $dt->format('d/m/Y') : '—' }}</td>
                <td class="px-4 py-2">{{ $dt ? $dt->format('H:i') : '—' }}</td>
                <td class="px-4 py-2 font-medium">{{ number_format($row['amount'],2) }} XAF</td>
                <td class="px-4 py-2 text-gray-600">{{ $row['count'] }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div id="clients-today-empty" class="hidden py-8 text-center text-sm text-gray-500">Aucune collecte enregistrée aujourd’hui.</div>
    @endif
  </div>

</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
  const statsUrl = "{{ route('agent.dashboard.stats') }}";

  const gd = document.getElementById('chartDaily');
  const gt = document.getElementById('chartTiming');

  const initialDailyLabels = @json($dailyLabels ?? []);
  const initialDailyValues = @json($dailyValues ?? []);
  const initialTiming = @json($timingCounts ?? ['on_time'=>0,'early'=>0,'late'=>0]);

  let chartDaily = null, chartTiming = null;

  if (gd){
    chartDaily = new Chart(gd, {
      type: 'line',
      data: {
        labels: initialDailyLabels,
        datasets: [{
          label: 'Montant',
          data: initialDailyValues,
          tension: .35,
          borderColor: '#0078B7',
          backgroundColor: 'rgba(0,120,183,0.15)',
          fill: true, pointRadius: 3, pointHoverRadius: 6
        }]
      },
      options: {
        responsive: true, maintainAspectRatio: false,
        interaction: { mode:'index', intersect:false },
        scales: { y: { beginAtZero:true }, x: { grid:{ display:false } } },
        plugins: { legend:{ display:false } }
      }
    });
  }

  if (gt){
    chartTiming = new Chart(gt, {
      type: 'doughnut',
      data: {
        labels: ['Conforme','Anticipé','Retardé'],
        datasets: [{
          data: [initialTiming.on_time||0, initialTiming.early||0, initialTiming.late||0],
          backgroundColor: ['#22c55e','#3b82f6','#f59e0b'],
          borderColor:'#fff', borderWidth:2, hoverOffset:6
        }]
      },
      options: { cutout:'55%', responsive:true, plugins:{ legend:{ position:'bottom' } } }
    });
  }

  function formatAmount(x){
    try { return new Intl.NumberFormat('fr-FR',{minimumFractionDigits:2,maximumFractionDigits:2}).format(x); }
    catch(e){ return Number(x).toFixed(2); }
  }

  function fmtDateTime(iso){
    try{
      const d = new Date(iso);
      return { date: d.toLocaleDateString('fr-FR'), time: d.toLocaleTimeString('fr-FR',{hour:'2-digit',minute:'2-digit'}) };
    } catch(e){ return {date:'—', time:'—'}; }
  }

  function renderClientsToday(rows){
    const wrap = document.getElementById('clients-today-table-wrap');
    const empty = document.getElementById('clients-today-empty');
    const tbody = document.getElementById('clients-today-body');
    const badge = document.getElementById('kpi-clients-collected-today-badge');
    if (!wrap || !empty || !tbody) return;

    const count = (rows || []).length;
    if (badge) badge.textContent = `${count} client(s)`;

    if (!rows || count === 0){
      empty.classList.remove('hidden');
      wrap.classList.add('hidden');
      tbody.innerHTML = '';
      return;
    }
    empty.classList.add('hidden');
    wrap.classList.remove('hidden');

    tbody.innerHTML = rows.map(r => {
      const t = r.last_created_at ? fmtDateTime(r.last_created_at) : {date:'—', time:'—'};
      return `
        <tr class="hover:bg-gray-50">
          <td class="px-4 py-2">${r.name ?? '—'}</td>
          <td class="px-4 py-2">${r.address ?? '—'}</td>
          <td class="px-4 py-2">${t.date}</td>
          <td class="px-4 py-2">${t.time}</td>
          <td class="px-4 py-2 font-medium">${formatAmount(r.amount)} XAF</td>
          <td class="px-4 py-2 text-gray-600">${r.count}</td>
        </tr>
      `;
    }).join('');
  }

  async function refreshDashboard(){
    try {
      const res = await fetch(statsUrl, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) return;
      const data = await res.json();

      // KPIs
      const elToday = document.getElementById('kpi-today-amount');
      const elClientsToday = document.getElementById('kpi-clients-collected-today');
      const elActive = document.getElementById('kpi-active-tontines');
      const elClientsCount = document.getElementById('kpi-clients-count');

      if (elToday) elToday.textContent = `${formatAmount(data.todayAmount || 0)} XAF`;
      if (elClientsToday) elClientsToday.textContent = data.clientsCollectedTodayCount || 0;
      if (elActive) elActive.textContent = data.activeTontines || 0;
      if (elClientsCount) elClientsCount.textContent = data.clientsCount || 0;

      // Courbe
      if (chartDaily && data.dailyLabels && data.dailyValues){
        chartDaily.data.labels = data.dailyLabels;
        chartDaily.data.datasets[0].data = data.dailyValues;
        chartDaily.update('active');
      }

      // Donut timing
      if (chartTiming && data.timingCounts){
        const t = data.timingCounts;
        chartTiming.data.datasets[0].data = [t.on_time||0, t.early||0, t.late||0];
        chartTiming.update('active');
      }

      // Table
      renderClientsToday(data.clientDailyStats || []);
    } catch(e) {}
  }

  refreshDashboard();
  setInterval(refreshDashboard, 15000);
})();
</script>
@endsection