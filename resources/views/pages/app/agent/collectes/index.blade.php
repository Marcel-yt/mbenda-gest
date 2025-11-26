@extends('layouts.app-agent')

@section('title', 'Collectes')
@section('page_title', 'Calendrier des collectes')

@section('content')
@php /** @var \App\Models\Tontine $tontine */ @endphp
@php /** @var array $days */ @endphp

<div class="max-w-7xl mx-auto space-y-6">

  <!-- Breadcrumb -->
  <nav class="flex items-center gap-2 text-sm text-gray-600">
    <a href="{{ route('agent.tontines.index') }}" class="hover:text-gray-900 transition-colors">
      <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
        <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
        <circle cx="12" cy="14" r="2"/>
      </svg>
    </a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <a href="{{ route('agent.tontines.index') }}" class="hover:text-gray-900 transition-colors">Tontines</a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <a href="{{ route('agent.tontines.show', $tontine) }}" class="hover:text-gray-900 transition-colors">{{ $tontine->code }}</a>
    <svg class="w-4 h-4 text-gray-400" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
      <polyline points="9 18 15 12 9 6"/>
    </svg>
    <span class="font-medium text-gray-900">Calendrier</span>
  </nav>

  <!-- Header Card -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl shadow-sm border border-gray-100 p-6">
    <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
      <div class="flex items-start gap-4">
        <div class="w-14 h-14 rounded-lg flex items-center justify-center text-white shadow-lg" style="background: linear-gradient(135deg, var(--mb-primary) 0%, #005f8d 100%);">
          <svg class="w-7 h-7" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="18" rx="2" ry="2"/>
            <line x1="16" y1="2" x2="16" y2="6"/>
            <line x1="8" y1="2" x2="8" y2="6"/>
            <line x1="3" y1="10" x2="21" y2="10"/>
          </svg>
        </div>
        <div>
          <h1 class="text-2xl font-bold text-gray-900">Calendrier des collectes</h1>
          <div class="flex items-center gap-3 mt-2">
            <span class="text-sm text-gray-600">Tontine:</span>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold" style="background-color: rgba(127, 188, 71, 0.1); color: var(--mb-secondary);">
              {{ $tontine->code }}
            </span>
          </div>
          <div class="flex items-center gap-2 mt-1 text-sm text-gray-600">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
              <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/>
              <circle cx="9" cy="7" r="4"/>
              <path d="M22 21v-2a4 4 0 0 0-3-3.87"/>
              <path d="M16 3.13a4 4 0 0 1 0 7.75"/>
            </svg>
            <span>{{ $tontine->client?->first_name }} {{ $tontine->client?->last_name }}</span>
          </div>
        </div>
      </div>
      <div class="flex items-center gap-2">
        <a href="{{ route('agent.tontines.show', $tontine) }}" 
           class="inline-flex items-center gap-2 px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
          </svg>
          Retour
        </a>
      </div>
    </div>
  </div>

  <!-- Legend Card -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
    <div class="flex flex-wrap items-center gap-4 md:gap-6 text-sm">
      <div class="flex items-center gap-2">
        <span class="w-4 h-4 bg-gray-100 rounded border border-gray-300"></span>
        <span class="text-gray-700">Non collecté</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="w-4 h-4 rounded border-2" style="background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%); border-color: var(--mb-primary);"></span>
        <span class="text-gray-700">Aujourd'hui</span>
      </div>
      <div class="flex items-center gap-2">
        <span class="w-4 h-4 rounded border" style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%); border-color: var(--mb-secondary);"></span>
        <span class="text-gray-700">Collecté</span>
      </div>
      <div class="ml-auto flex items-center gap-2 text-xs text-gray-500">
        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
          <circle cx="12" cy="12" r="10"/>
          <line x1="12" y1="16" x2="12" y2="12"/>
          <line x1="12" y1="8" x2="12.01" y2="8"/>
        </svg>
        <span class="hidden sm:inline">Cliquez sur une case pour collecter ou voir les détails</span>
        <span class="sm:hidden">Cliquez pour collecter</span>
      </div>
    </div>
  </div>

  <!-- Calendar Grid -->
  <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 md:p-6">
    <div class="grid grid-cols-4 gap-2 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-7 xl:grid-cols-7 sm:gap-3 md:gap-4">
      @foreach ($days as $d)
          @php
              $items = $d['collectes'];
              $last  = $items->sortByDesc('created_at')->first();
              $hex   = $last?->agent?->color_hex ? ltrim($last->agent->color_hex, '#') : '7FBC47';
              $bg    = $items->isNotEmpty() ? "linear-gradient(135deg, #{$hex}15 0%, #{$hex}25 100%)" : '#f9fafb';
              $bd    = $items->isNotEmpty() ? "#{$hex}" : '#e5e7eb';
              $isPaid = $tontine->status === 'paid';
                  // If the day already has collectes, open the show page for the last collecte;
                  // otherwise open the create page. If tontine is paid and no collecte exists,
                  // keep behavior that prevents creating (href="#" with alert handled later).
                  if ($items->isNotEmpty()) {
                    $href = route('agent.collectes.show', $last?->id);
                  } else {
                    $href = $isPaid ? '#' : route('agent.collectes.create', ['tontine_id' => $tontine->id, 'date' => $d['date']]);
                  }
              $isToday = $d['is_today'] ?? \Illuminate\Support\Carbon::parse($d['date'])->isToday();
          @endphp

          <a href="{{ $href }}"
             class="group relative block rounded-xl border-2 p-2 sm:p-3 md:p-4 text-xs sm:text-sm min-h-[85px] sm:min-h-[100px] md:min-h-[110px] transition-all duration-200 {{ $isPaid && $items->isEmpty() ? 'opacity-50 cursor-not-allowed' : 'hover:shadow-lg hover:scale-105 cursor-pointer' }} {{ $isToday ? 'ring-2 ring-offset-2 shadow-md' : '' }}"
             style="background: {{ $bg }}; border-color: {{ $bd }}; {{ $isToday ? 'ring-color: var(--mb-primary);' : '' }}"
             {{ $isPaid && $items->isEmpty() ? 'onclick="event.preventDefault(); alert(\'Cette tontine a été payée. Il n\\\'est plus possible d\\\'enregistrer de nouvelles collectes.\');"' : '' }}>

              <div class="flex items-center justify-between mb-2">
                <span class="inline-flex items-center justify-center w-6 h-6 sm:w-7 sm:h-7 rounded-full text-[11px] sm:text-xs font-bold {{ $items->isNotEmpty() ? 'text-white' : 'bg-gray-200 text-gray-600' }}"
                      style="{{ $items->isNotEmpty() ? "background-color: #{$hex};" : '' }}">
                  {{ $d['day'] }}
                </span>
                @if ($isToday)
                  <span class="inline-flex items-center px-1.5 py-0.5 rounded-full text-[9px] sm:text-[10px] font-semibold bg-blue-100 text-blue-700">
                    Aujourd'hui
                  </span>
                @endif
              </div>

              @if ($items->isNotEmpty())
                <div class="space-y-1">
                  <div class="flex items-center gap-1.5">
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" style="color: #{{ $hex }};" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                      <polyline points="20 6 9 17 4 12"/>
                    </svg>
                    <span class="font-semibold text-[11px] sm:text-xs" style="color: #{{ $hex }};">Collecté</span>
                  </div>

                  <div class="hidden sm:flex items-center gap-1 text-[10px] sm:text-xs text-gray-600">
                    <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"/>
                      <polyline points="12 6 12 12 16 14"/>
                    </svg>
                    <span>{{ optional($items->sortBy('created_at')->last()->created_at)->format('H:i') }}</span>
                  </div>

                  @if ($items->count() > 1)
                    <div class="flex items-center gap-1 text-[10px] sm:text-xs font-medium" style="color: #{{ $hex }};">
                      <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/>
                        <polyline points="7 10 12 15 17 10"/>
                        <line x1="12" y1="15" x2="12" y2="3"/>
                      </svg>
                      <span>+{{ $items->count() - 1 }}</span>
                    </div>
                  @endif

                  <div class="mt-2 hidden sm:flex items-center gap-1.5 text-[10px] text-gray-700">
                    <span class="inline-block w-2.5 h-2.5 rounded-full" style="background-color: #{{ $hex }}"></span>
                    <span class="truncate">{{ $last?->agent?->first_name }}</span>
                  </div>
                </div>
              @else
                <div class="flex flex-col items-center justify-center mt-2 sm:mt-4 text-center">
                  @if($isPaid)
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-red-300 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"/>
                      <line x1="15" y1="9" x2="9" y2="15"/>
                      <line x1="9" y1="9" x2="15" y2="15"/>
                    </svg>
                    <span class="text-[10px] sm:text-xs text-red-400 font-medium hidden sm:block">Tontine</span>
                    <span class="text-[10px] sm:text-xs text-red-400 hidden sm:block">payée</span>
                  @else
                    <svg class="w-6 h-6 sm:w-8 sm:h-8 text-gray-300 mb-1" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                      <circle cx="12" cy="12" r="10"/>
                      <line x1="12" y1="8" x2="12" y2="12"/>
                      <line x1="12" y1="16" x2="12.01" y2="16"/>
                    </svg>
                    <span class="text-[10px] sm:text-xs text-gray-400 font-medium hidden sm:block">Cliquer</span>
                    <span class="text-[10px] sm:text-xs text-gray-400 hidden sm:block">pour collecter</span>
                  @endif
                </div>
                @if(!$isPaid)
                  <div class="absolute inset-0 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none" style="background: linear-gradient(135deg, rgba(127, 188, 71, 0.05) 0%, rgba(127, 188, 71, 0.1) 100%);"></div>
                @endif
              @endif

              @if ($isToday)
                <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-blue-500 animate-ping"></div>
                <div class="absolute -top-1 -right-1 w-3 h-3 rounded-full bg-blue-500"></div>
              @endif
          </a>
      @endforeach
    </div>

    <!-- Summary Footer -->
    <div class="mt-6 pt-4 border-t border-gray-100">
      <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
        @php
          $totalDays = count($days);
          $collectedDays = collect($days)->filter(fn($d) => $d['collectes']->isNotEmpty())->count();
          $remainingDays = $totalDays - $collectedDays;
          $progressPercent = $totalDays > 0 ? round(($collectedDays / $totalDays) * 100) : 0;
        @endphp
        <div class="bg-gray-50 rounded-lg p-3">
          <div class="text-2xl font-bold text-gray-900">{{ $totalDays }}</div>
          <div class="text-xs text-gray-600 mt-1">Jours total</div>
        </div>
        <div class="rounded-lg p-3" style="background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);">
          <div class="text-2xl font-bold" style="color: var(--mb-secondary);">{{ $collectedDays }}</div>
          <div class="text-xs mt-1" style="color: var(--mb-secondary);">Collectés</div>
        </div>
        <div class="bg-orange-50 rounded-lg p-3">
          <div class="text-2xl font-bold text-orange-700">{{ $remainingDays }}</div>
          <div class="text-xs text-orange-600 mt-1">Restants</div>
        </div>
        <div class="bg-blue-50 rounded-lg p-3">
          <div class="text-2xl font-bold text-blue-700">{{ $progressPercent }}%</div>
          <div class="text-xs text-blue-600 mt-1">Progression</div>
        </div>
      </div>
    </div>
  </div>

</div>
@endsection
