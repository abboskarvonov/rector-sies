@extends('layouts.app')

@section('title', 'Bosh sahifa')

@push('styles')
    <style>
        .hero-pattern {
            background-color: #15803d;
            background-image:
                radial-gradient(circle at 20% 50%, rgba(255, 255, 255, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, rgba(255, 255, 255, 0.07) 0%, transparent 40%),
                radial-gradient(circle at 60% 80%, rgba(0, 0, 0, 0.1) 0%, transparent 40%);
        }

        .card-hover {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.12);
        }

        .step-line::after {
            content: '';
            position: absolute;
            top: 20px;
            left: calc(50% + 28px);
            width: calc(100% - 56px);
            height: 2px;
            background: #bbf7d0;
        }

        @media (max-width: 767px) {
            .step-line::after {
                display: none;
            }
        }
    </style>
@endpush

@section('content')

    {{-- ===== HERO ===== --}}
    <section class="hero-pattern rounded-3xl overflow-hidden mb-10 -mt-2">
        <div class="px-6 py-14 sm:py-20 flex flex-col items-center text-center">

            {{-- Emblem --}}
            <div
                class="w-24 h-24 sm:w-32 sm:h-32 bg-white rounded-full flex items-center justify-center shadow-xl mb-6 ring-4 ring-white/30">
                <svg class="w-14 h-14 sm:w-20 sm:h-20 text-primary-700" viewBox="0 0 80 80" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    {{-- Graduation cap --}}
                    <path d="M40 12L8 28L40 44L72 28L40 12Z" fill="currentColor" opacity="0.9" />
                    <path d="M24 35v14c0 4.4 7.2 8 16 8s16-3.6 16-8V35L40 44 24 35Z" fill="currentColor" opacity="0.7" />
                    <rect x="68" y="28" width="4" height="16" rx="2" fill="currentColor" opacity="0.6" />
                    <circle cx="70" cy="46" r="3" fill="currentColor" opacity="0.6" />
                    {{-- Book lines --}}
                    <path d="M34 24h12M34 29h8" stroke="white" stroke-width="1.5" stroke-linecap="round" opacity="0.5" />
                </svg>
            </div>

            {{-- Institute name --}}
            <p class="text-primary-200 text-xs sm:text-sm font-medium tracking-widest uppercase mb-2">
                Samarqand iqtisodiyot va servis instituti
            </p>
            <h1 class="text-white text-2xl sm:text-4xl font-bold leading-tight max-w-2xl mb-2">
                Rektori virtual qabulxonasi
            </h1>
            <p class="text-primary-100 text-sm sm:text-base max-w-xl mt-3 leading-relaxed opacity-90">
                Murojaat yuboring, holati kuzating. Har bir murojaat ko'rib chiqilib,
                3 ish kuni ichida javob beriladi.
            </p>

            {{-- CTA buttons --}}
            <div class="flex flex-col sm:flex-row gap-3 mt-8">
                <a href="{{ route('appeals.create') }}"
                    class="inline-flex items-center justify-center gap-2 bg-white text-primary-700 font-bold px-8 py-3.5 rounded-xl shadow-lg hover:bg-primary-50 transition text-sm sm:text-base">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Murojaat yuborish
                </a>
                <a href="{{ route('tracking.index') }}"
                    class="inline-flex items-center justify-center gap-2 bg-primary-600/60 hover:bg-primary-600/80 text-white font-semibold px-8 py-3.5 rounded-xl border border-white/20 transition text-sm sm:text-base backdrop-blur-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z" />
                    </svg>
                    Murojaatni tekshirish
                </a>
            </div>
        </div>
    </section>

    {{-- ===== ACTION CARDS ===== --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 gap-5 mb-12">

        <a href="{{ route('appeals.create') }}"
            class="card-hover block bg-white rounded-2xl border border-gray-100 shadow-sm p-7 group">
            <div
                class="w-12 h-12 bg-primary-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-primary-200 transition">
                <svg class="w-6 h-6 text-primary-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-primary-700 transition">Murojaat yuborish</h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Rektorga shikoyat, taklif yoki so'rovingizni onlayn yuboring.
                Barcha murojaatlar maxfiy saqlanadi.
            </p>
            <span
                class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-primary-600 group-hover:gap-2 transition-all">
                Boshlash
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        </a>

        <a href="{{ route('tracking.index') }}"
            class="card-hover block bg-white rounded-2xl border border-gray-100 shadow-sm p-7 group">
            <div
                class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:bg-blue-200 transition">
                <svg class="w-6 h-6 text-blue-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                </svg>
            </div>
            <h2 class="text-lg font-bold text-gray-800 mb-1 group-hover:text-blue-700 transition">Murojaatni kuzatish</h2>
            <p class="text-sm text-gray-500 leading-relaxed">
                Tracking kodi orqali murojaatingiz holatini, ko'rib chiqish jarayonini
                va javobini kuzating.
            </p>
            <span
                class="inline-flex items-center gap-1 mt-4 text-sm font-semibold text-blue-600 group-hover:gap-2 transition-all">
                Tekshirish
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </span>
        </a>

    </section>

    {{-- ===== HOW IT WORKS ===== --}}
    <section class="mb-12">
        <h2 class="text-xl font-bold text-gray-800 text-center mb-8">Qanday ishlaydi?</h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            @php
                $steps = [
                    [
                        'number' => '01',
                        'icon' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>',
                        'title' => 'Murojaat yuboring',
                        'text' => 'Kategoriyani tanlang, ma\'lumotlaringizni kiriting va murojaatingizni yuboring.',
                        'color' => 'primary',
                    ],
                    [
                        'number' => '02',
                        'icon' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>',
                        'title' => 'Tracking kodi oling',
                        'text' => 'Yuborishdan so\'ng sizga unikal tracking kodi beriladi. Uni saqlang.',
                        'color' => 'blue',
                    ],
                    [
                        'number' => '03',
                        'icon' =>
                            '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                        'title' => 'Javob kuting',
                        'text' => '3 ish kuni ichida murojaatingiz ko\'rib chiqiladi va javob beriladi.',
                        'color' => 'purple',
                    ],
                ];
                $colors = [
                    'primary' => [
                        'bg' => 'bg-primary-100',
                        'icon' => 'text-primary-600',
                        'num' => 'text-primary-700 bg-primary-50 border-primary-200',
                    ],
                    'blue' => [
                        'bg' => 'bg-blue-100',
                        'icon' => 'text-blue-600',
                        'num' => 'text-blue-700 bg-blue-50 border-blue-200',
                    ],
                    'purple' => [
                        'bg' => 'bg-purple-100',
                        'icon' => 'text-purple-600',
                        'num' => 'text-purple-700 bg-purple-50 border-purple-200',
                    ],
                ];
            @endphp

            @foreach ($steps as $i => $step)
                @php $c = $colors[$step['color']]; @endphp
                <div class="relative bg-white rounded-2xl border border-gray-100 shadow-sm p-6">

                    {{-- Connector (desktop) --}}
                    @if (!$loop->last)
                        <div class="hidden md:block absolute top-9 left-[calc(100%-1px)] w-6 z-10">
                            <svg class="w-6 h-4 text-gray-300" viewBox="0 0 24 16" fill="none">
                                <path d="M0 8h20M16 3l6 5-6 5" stroke="currentColor" stroke-width="1.5"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                    @endif

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 {{ $c['bg'] }} rounded-xl flex items-center justify-center shrink-0">
                            <svg class="w-6 h-6 {{ $c['icon'] }}" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                {!! $step['icon'] !!}
                            </svg>
                        </div>
                        <div>
                            <span
                                class="text-xs font-bold {{ $c['num'] }} border rounded-full px-2 py-0.5 mb-2 inline-block">
                                {{ $step['number'] }}
                            </span>
                            <h3 class="font-semibold text-gray-800 mb-1">{{ $step['title'] }}</h3>
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $step['text'] }}</p>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>
    </section>

    {{-- ===== INFO STRIP ===== --}}
    <section class="bg-primary-700 rounded-2xl p-6 sm:p-8">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 text-center">

            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>
                <p class="text-white font-semibold text-sm">Maxfiylik kafolati</p>
                <p class="text-primary-200 text-xs">Murojaatlar faqat vakolatli xodimlarga ko'rinadi</p>
            </div>

            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <p class="text-white font-semibold text-sm">3 ish kuni ichida</p>
                <p class="text-primary-200 text-xs">Har bir murojaat belgilangan muddatda ko'rib chiqiladi</p>
            </div>

            <div class="flex flex-col items-center gap-2">
                <div class="w-10 h-10 bg-primary-600 rounded-full flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                    </svg>
                </div>
                <p class="text-white font-semibold text-sm">Rasmiy javob</p>
                <p class="text-primary-200 text-xs">Institut rahbariyati tomonidan rasmiy javob beriladi</p>
            </div>

        </div>
    </section>

@endsection
