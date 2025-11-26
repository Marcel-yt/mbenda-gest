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

  {{-- Paiements aujourd'hui (nouveau) --}}
  @php
    use App\Models\Payout;
    $todayPayoutsCount = 0;
    $todayPayoutsSum = 0;
    try {
      $today = now()->toDateString();
      $todayPayouts = Payout::whereDate('created_at', $today)->where('paid_by_admin_id', auth()->id());
      $todayPayoutsCount = $todayPayouts->count();
      $todayPayoutsSum = (float) $todayPayouts->sum('amount_net');
    } catch (\Throwable $e) {
      // silence in view
    }
  @endphp

  <div class="mt-4">
    <div class="bg-white border rounded-xl p-5 flex items-center justify-between">
      <div>
        <div class="text-sm text-gray-500 uppercase">Paiements aujourd'hui</div>
        <div class="mt-2 text-2xl font-bold text-gray-900">{{ $todayPayoutsCount }} paiement(s)</div>
        <div class="text-xs text-gray-500 mt-1">Total: {{ number_format($todayPayoutsSum, 2, ',', ' ') }} XAF</div>
      </div>
      <div>
        <a href="{{ route('agent.payouts.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-indigo-500 to-indigo-600 text-white rounded-lg text-sm font-medium">
          Voir les paiements
        </a>
      </div>
    </div>
  </div>

  {{-- Graphiques analytiques --}}
  <div class="grid gap-6 lg:grid-cols-3">
    {{-- Courbe évolution 30 jours --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 lg:col-span-2 shadow-sm hover:shadow-md transition-shadow min-w-0">
      <div class="flex items-start justify-between mb-6">
        <div>
          <h2 class="text-lg font-bold text-gray-900">Évolution des collectes</h2>
          <p class="text-sm text-gray-500 mt-1">30 derniers jours</p>
        </div>
        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-100 to-blue-200 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
          </svg>
        </div>
      </div>
      <div class="h-72 w-full max-w-full overflow-hidden">
        <canvas id="chartDaily" class="w-full block" aria-hidden="true"></canvas>
      </div>
    </div>

    {{-- Donut timing collectes --}}
    <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md transition-shadow min-w-0">
      <div class="flex items-start justify-between mb-6">
        <div>
          <h2 class="text-lg font-bold text-gray-900">Ponctualité</h2>
          <p class="text-sm text-gray-500 mt-1">Collectes du jour</p>
        </div>
        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
          </svg>
        </div>
      </div>
      <div class="h-72 w-full max-w-full overflow-hidden flex items-center justify-center">
        <canvas id="chartTiming" class="w-full block max-w-[320px] mx-auto" aria-hidden="true"></canvas>
      </div>
    </div>
  </div>

  {{-- Liste des clients collectés aujourd'hui --}}
  <div class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-gradient-to-br from-indigo-100 to-indigo-200 rounded-lg flex items-center justify-center">
          <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
          </svg>
        </div>
        <div>
          <h2 class="text-lg font-bold text-gray-900">Clients collectés aujourd'hui</h2>
          <p class="text-sm text-gray-500">Détail des collectes effectuées</p>
        </div>
      </div>
      <span id="kpi-clients-collected-today-badge" class="px-3 py-1 text-xs font-semibold text-indigo-700 bg-indigo-100 rounded-full">{{ $clientsCollectedTodayCount }} client(s)</span>
    </div>

    @if($clientDailyStats->isEmpty())
      <div id="clients-today-empty" class="py-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <p class="text-sm font-medium text-gray-900 mb-1">Aucune collecte aujourd'hui</p>
        <p class="text-xs text-gray-500">Les collectes apparaîtront ici dès qu'elles seront enregistrées</p>
      </div>
      <div class="overflow-x-auto hidden" id="clients-today-table-wrap">
        <table class="min-w-full text-sm">
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-y border-gray-200">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Client</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Adresse</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Heure</th>
              <th class="px-5 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Montant total</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Collectes</th>
            </tr>
          </thead>
          <tbody id="clients-today-body" class="divide-y divide-gray-100 bg-white"></tbody>
        </table>
      </div>
    @else
      <div class="overflow-x-auto" id="clients-today-table-wrap">
        <table class="min-w-full text-sm">
          <thead class="bg-gradient-to-r from-gray-50 to-gray-100 border-y border-gray-200">
            <tr>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Client</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Adresse</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Date</th>
              <th class="px-5 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Heure</th>
              <th class="px-5 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Montant total</th>
              <th class="px-5 py-3 text-center text-xs font-semibold text-gray-700 uppercase tracking-wider">Collectes</th>
            </tr>
          </thead>
          <tbody id="clients-today-body" class="divide-y divide-gray-100 bg-white">
            @foreach($clientDailyStats as $row)
              @php
                $dt = $row['last_created_at'] ? \Carbon\Carbon::parse($row['last_created_at']) : null;
              @endphp
              <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 transition-colors">
                <td class="px-5 py-4">
                  <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                      {{ strtoupper(substr($row['name'] ?? 'C', 0, 1)) }}
                    </div>
                    <span class="font-medium text-gray-900">{{ $row['name'] }}</span>
                  </div>
                </td>
                <td class="px-5 py-4 text-gray-600">{{ $row['address'] ?? '—' }}</td>
                <td class="px-5 py-4 text-gray-600">{{ $dt ? $dt->format('d/m/Y') : '—' }}</td>
                <td class="px-5 py-4">
                  <span class="inline-flex items-center gap-1 text-gray-600">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ $dt ? $dt->format('H:i') : '—' }}
                  </span>
                </td>
                <td class="px-5 py-4 text-right">
                  <span class="font-bold text-green-600">{{ number_format($row['amount'],2) }} XAF</span>
                </td>
                <td class="px-5 py-4 text-center">
                  <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">{{ $row['count'] }}</span>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div id="clients-today-empty" class="hidden py-12 text-center">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
          <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
          </svg>
        </div>
        <p class="text-sm font-medium text-gray-900 mb-1">Aucune collecte aujourd'hui</p>
        <p class="text-xs text-gray-500">Les collectes apparaîtront ici dès qu'elles seront enregistrées</p>
      </div>
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
      const initial = (r.name && r.name.length > 0) ? r.name.charAt(0).toUpperCase() : 'C';
      return `
        <tr class="hover:bg-gradient-to-r hover:from-indigo-50 hover:to-blue-50 transition-colors">
          <td class="px-5 py-4">
            <div class="flex items-center gap-3">
              <div class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-blue-500 rounded-full flex items-center justify-center text-white text-xs font-semibold">
                ${initial}
              </div>
              <span class="font-medium text-gray-900">${r.name ?? '—'}</span>
            </div>
          </td>
          <td class="px-5 py-4 text-gray-600">${r.address ?? '—'}</td>
          <td class="px-5 py-4 text-gray-600">${t.date}</td>
          <td class="px-5 py-4">
            <span class="inline-flex items-center gap-1 text-gray-600">
              <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
              </svg>
              ${t.time}
            </span>
          </td>
          <td class="px-5 py-4 text-right">
            <span class="font-bold text-green-600">${formatAmount(r.amount)} XAF</span>
          </td>
          <td class="px-5 py-4 text-center">
            <span class="inline-flex items-center justify-center w-8 h-8 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">${r.count}</span>
          </td>
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