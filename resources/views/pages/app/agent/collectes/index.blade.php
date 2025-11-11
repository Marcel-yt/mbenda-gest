@extends('layouts.app-agent')

@section('title', 'Collectes')
@section('page_title', 'Calendrier des collectes')

@section('content')
@php /** @var \App\Models\Tontine $tontine */ @endphp
@php /** @var array $days */ @endphp

<div class="max-w-4xl mx-auto space-y-6">
  <div class="flex items-center justify-between">
    <div>
      <h2 class="text-lg font-semibold">Collectes — {{ $tontine->code }}</h2>
      <div class="text-sm text-gray-600">{{ $tontine->client?->first_name }} {{ $tontine->client?->last_name }}</div>
    </div>
    <div class="flex items-center gap-2">
      <a href="{{ route('agent.tontines.show', $tontine) }}" class="mb-btn-primary px-3 py-2 rounded">← Retour tontine</a>
    </div>
  </div>

  {{-- Legend --}}
  <div class="flex items-center gap-4 text-sm">
    <div class="inline-flex items-center gap-2">
      <span class="w-3 h-3 bg-gray-100 rounded-sm inline-block border"></span> Non collecté
    </div>
    <div class="inline-flex items-center gap-2">
      <span class="w-3 h-3 bg-blue-100 rounded-sm inline-block border"></span> Aujourd'hui
    </div>
  </div>

  {{-- Calendar grid: 7 cols to look like a calendar, will render all days (e.g. 31) --}}
  <div class="grid grid-cols-4 gap-2 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-6 xl:grid-cols-7 sm:gap-3 md:gap-4">
    @foreach ($days as $d)
        @php
            $items = $d['collectes'];
            $last  = $items->sortByDesc('created_at')->first();
            $hex   = $last?->agent?->color_hex ? ltrim($last->agent->color_hex, '#') : '22c55e';
            $bg    = "#{$hex}1A";
            $bd    = "#{$hex}";
            $href  = $items->isNotEmpty()
                ? route('agent.collectes.show', $last?->id)
                : route('agent.collectes.create', ['tontine_id' => $tontine->id, 'date' => $d['date']]);
            $isToday = $d['is_today'] ?? \Illuminate\Support\Carbon::parse($d['date'])->isToday();
        @endphp

        {{-- CARTE: classes compactes et responsives (même balise/structure qu’avant) --}}
       <a href="{{ $href }}"
          class="block rounded-lg border p-2 sm:p-3 md:p-4 text-xs sm:text-sm min-h-[78px] sm:min-h-[90px] transition hover:shadow cursor-pointer {{ $isToday ? 'ring-2 ring-[--var(--primary)] ring-offset-1 animate-pulse' : '' }}"
          style="{{ $items->isNotEmpty() ? "background-color: {$bg}; border-color: {$bd};" : '' }}">

            {{-- LIGNE "Jour" plus petite en mobile --}}
           <div class="text-[11px] sm:text-xs text-gray-500">Jour {{ $d['day'] }}</div>

            @if ($items->isNotEmpty())
               <div class="mt-0.5 sm:mt-1 font-semibold text-[12px] sm:text-sm">Collecté</div>

                {{-- Date/heure: cachée en XS, visible dès sm --}}
               <div class="hidden sm:block text-xs text-gray-600">
                   {{ optional($items->sortBy('created_at')->last()->created_at)->format('d/m H:i') }}
                   @if ($items->count() > 1) <span class="ml-1">+{{ $items->count() - 1 }}</span> @endif
               </div>
               @if ($items->count() > 1)
                   <div class="sm:hidden text-[11px] text-gray-600">+{{ $items->count() - 1 }}</div>
               @endif

                {{-- Pastille couleur; nom agent masqué en XS --}}
               <div class="mt-1 sm:mt-2 inline-flex items-center gap-1 sm:gap-2 text-[11px] sm:text-xs">
                   <span class="inline-block w-3 h-3 rounded-full" style="background-color: #{{ $hex }}"></span>
                   <span class="hidden sm:inline text-gray-700">{{ $last?->agent?->first_name }} </span>
               </div>
            @else
               <div class="mt-2 sm:mt-4 text-gray-400 text-[12px] sm:text-sm">Cliquer pour collecter</div>
            @endif
        </a>
    @endforeach
  </div>
</div>
@endsection