@extends('layouts.app')

@section('title', 'Murojaat holati — ' . $appeal->tracking_code)

@section('content')

@php
    $statusMeta = [
        'pending'    => ['label' => 'Kutilmoqda',      'color' => 'yellow',  'icon' => '🕐'],
        'reviewing'  => ['label' => 'Ko\'rib chiqilmoqda', 'color' => 'blue', 'icon' => '🔍'],
        'responded'  => ['label' => 'Javob berildi',   'color' => 'green',   'icon' => '✅'],
        'closed'     => ['label' => 'Yopildi',         'color' => 'gray',    'icon' => '🔒'],
        'rejected'   => ['label' => 'Rad etildi',      'color' => 'red',     'icon' => '❌'],
    ];

    $colorMap = [
        'yellow' => ['badge' => 'bg-yellow-100 text-yellow-800 border-yellow-200',  'dot' => 'bg-yellow-400', 'ring' => 'ring-yellow-300'],
        'blue'   => ['badge' => 'bg-blue-100 text-blue-800 border-blue-200',        'dot' => 'bg-blue-500',   'ring' => 'ring-blue-300'],
        'green'  => ['badge' => 'bg-primary-100 text-primary-800 border-primary-200', 'dot' => 'bg-primary-500', 'ring' => 'ring-primary-300'],
        'gray'   => ['badge' => 'bg-gray-100 text-gray-700 border-gray-200',        'dot' => 'bg-gray-400',   'ring' => 'ring-gray-300'],
        'red'    => ['badge' => 'bg-red-100 text-red-800 border-red-200',           'dot' => 'bg-red-500',    'ring' => 'ring-red-300'],
    ];

    $current     = $statusMeta[$appeal->status] ?? $statusMeta['pending'];
    $currentColor = $colorMap[$current['color']];

    $timeline = [
        ['key' => 'pending',   'label' => 'Qabul qilindi'],
        ['key' => 'reviewing', 'label' => 'Ko\'rib chiqilmoqda'],
        ['key' => 'responded', 'label' => 'Javob berildi'],
    ];

    $statusOrder = ['pending' => 0, 'reviewing' => 1, 'responded' => 2, 'closed' => 2, 'rejected' => 2];
    $currentOrder = $statusOrder[$appeal->status] ?? 0;
@endphp

<div class="max-w-3xl mx-auto space-y-6">

    {{-- ===== HEADER CARD ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-xs text-gray-400 uppercase tracking-wider mb-1">Tracking kodi</p>
            <span class="text-2xl font-bold font-mono text-primary-700 tracking-widest">
                {{ $appeal->tracking_code }}
            </span>
        </div>
        <div class="flex items-center gap-3">
            <span class="{{ $currentColor['badge'] }} border text-sm font-semibold px-4 py-1.5 rounded-full flex items-center gap-1.5">
                <span class="w-2 h-2 rounded-full {{ $currentColor['dot'] }} inline-block"></span>
                {{ $current['icon'] }} {{ $current['label'] }}
            </span>
        </div>
    </div>

    {{-- ===== STATUS TIMELINE ===== --}}
    @if(!in_array($appeal->status, ['closed', 'rejected']))
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-6">Murojaat holati</h2>

        <div class="flex items-start">
            @foreach($timeline as $i => $step)
                @php
                    $stepOrder = $statusOrder[$step['key']] ?? 0;
                    $isDone    = $currentOrder > $stepOrder;
                    $isActive  = $currentOrder === $stepOrder;
                @endphp

                {{-- Connector --}}
                @if($i > 0)
                    <div class="flex-1 mt-4 h-0.5 transition-colors {{ $isDone ? 'bg-primary-500' : 'bg-gray-200' }}"></div>
                @endif

                <div class="flex flex-col items-center text-center" style="min-width: 90px">
                    {{-- Circle --}}
                    <div class="w-9 h-9 rounded-full border-2 flex items-center justify-center mb-2 transition-all
                        {{ $isDone  ? 'bg-primary-600 border-primary-600'       : '' }}
                        {{ $isActive ? 'bg-white border-primary-500 ring-4 ring-primary-100' : '' }}
                        {{ !$isDone && !$isActive ? 'bg-white border-gray-300' : '' }}">
                        @if($isDone)
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        @elseif($isActive)
                            <div class="w-3 h-3 bg-primary-500 rounded-full animate-pulse"></div>
                        @else
                            <div class="w-2.5 h-2.5 bg-gray-300 rounded-full"></div>
                        @endif
                    </div>
                    <span class="text-xs font-medium leading-tight
                        {{ $isActive ? 'text-primary-600' : ($isDone ? 'text-gray-700' : 'text-gray-400') }}">
                        {{ $step['label'] }}
                    </span>
                </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Closed / Rejected banner --}}
    @if($appeal->status === 'closed')
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-4 flex gap-3 items-center">
            <span class="text-2xl">🔒</span>
            <p class="text-sm text-gray-600 font-medium">Murojaat yopildi. Zarurat bo'lsa yangi murojaat yuboring.</p>
        </div>
    @elseif($appeal->status === 'rejected')
        <div class="bg-red-50 border border-red-200 rounded-2xl p-4 flex gap-3 items-center">
            <span class="text-2xl">❌</span>
            <p class="text-sm text-red-700 font-medium">Murojaat rad etildi.</p>
        </div>
    @endif

    {{-- ===== ADMIN RESPONSE ===== --}}
    @if($appeal->responses->isNotEmpty())
        @foreach($appeal->responses as $response)
        <div class="bg-primary-50 border border-primary-200 rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-3">
                <svg class="w-5 h-5 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                </svg>
                <h3 class="font-semibold text-primary-800 text-sm">Muassasa javobi</h3>
                <span class="ml-auto text-xs text-primary-500">
                    {{ $response->created_at->format('d.m.Y H:i') }}
                </span>
            </div>
            <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-line">{{ $response->response_text }}</p>
        </div>
        @endforeach
    @endif

    {{-- ===== APPEAL DETAILS ===== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-5">Murojaat tafsilotlari</h2>

        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">To'liq ism</dt>
                <dd class="font-medium text-gray-800">{{ $appeal->full_name }}</dd>
            </div>
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Telefon</dt>
                <dd class="font-medium text-gray-800">{{ $appeal->phone }}</dd>
            </div>
            @if($appeal->email)
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Email</dt>
                <dd class="font-medium text-gray-800">{{ $appeal->email }}</dd>
            </div>
            @endif
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Kategoriya</dt>
                <dd class="font-medium text-gray-800">
                    {{ $appeal->category?->icon }} {{ $appeal->category?->name_uz ?? '—' }}
                </dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Mavzu</dt>
                <dd class="font-medium text-gray-800">{{ $appeal->subject }}</dd>
            </div>
            <div class="sm:col-span-2">
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-1.5">Murojaat matni</dt>
                <dd class="text-gray-700 leading-relaxed bg-gray-50 rounded-xl p-4 whitespace-pre-line border border-gray-100">{{ $appeal->body }}</dd>
            </div>
            @if($appeal->files)
            <div class="sm:col-span-2">
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-2">Ilovalar</dt>
                <dd class="flex flex-wrap gap-2">
                    @foreach($appeal->files as $file)
                        <a href="{{ Storage::url($file) }}" target="_blank"
                           class="flex items-center gap-1.5 text-xs bg-primary-50 hover:bg-primary-100 border border-primary-200 text-primary-700 font-medium px-3 py-1.5 rounded-lg transition">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"/>
                            </svg>
                            {{ basename($file) }}
                        </a>
                    @endforeach
                </dd>
            </div>
            @endif
            <div>
                <dt class="text-xs text-gray-400 uppercase tracking-wide mb-0.5">Yuborilgan sana</dt>
                <dd class="font-medium text-gray-800">{{ $appeal->created_at->format('d.m.Y H:i') }}</dd>
            </div>
        </dl>
    </div>

    {{-- ===== STATUS HISTORY ===== --}}
    @if($appeal->statuses->isNotEmpty())
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-sm font-semibold text-gray-500 uppercase tracking-wide mb-5">Holat tarixi</h2>
        <ol class="relative border-l-2 border-gray-200 space-y-5 ml-2">
            @foreach($appeal->statuses->sortByDesc('created_at') as $log)
                @php $meta = $statusMeta[$log->status] ?? $statusMeta['pending']; @endphp
                <li class="ml-5">
                    <span class="absolute -left-2 w-4 h-4 rounded-full border-2 border-white shadow {{ $colorMap[$meta['color']]['dot'] }}"></span>
                    <div class="flex flex-wrap items-center gap-2 mb-0.5">
                        <span class="text-xs font-semibold {{ str_contains($colorMap[$meta['color']]['badge'], 'text-') ? explode(' ', $colorMap[$meta['color']]['badge'])[1] : 'text-gray-700' }}">
                            {{ $meta['icon'] }} {{ $meta['label'] }}
                        </span>
                        <span class="text-xs text-gray-400">{{ $log->created_at->format('d.m.Y H:i') }}</span>
                    </div>
                    @if($log->comment)
                        <p class="text-sm text-gray-600">{{ $log->comment }}</p>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
    @endif

    <div class="text-center">
        <a href="{{ route('tracking.index') }}" class="text-sm text-primary-600 hover:text-primary-800 hover:underline transition">
            ← Boshqa murojaatni kuzatish
        </a>
    </div>

</div>
@endsection
