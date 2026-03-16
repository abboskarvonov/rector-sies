@extends('layouts.app')

@section('title', 'Bosh sahifa')

@push('styles')
    <style>
        .hero-pattern {
            background-color: #0f766e;
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
            background: #99f6e4;
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
                <img src="{{ asset('img/logo.webp') }}" alt="Logo" class="w-24 h-24 sm:w-32 sm:h-32 object-cover">
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

    {{-- ===== RECEPTION SCHEDULE ===== --}}
    <section class="mb-12">
        <div class="text-center mb-8">
            <span
                class="inline-block text-xs font-semibold tracking-widest uppercase text-primary-600 bg-primary-50 px-3 py-1 rounded-full mb-3">Qabul
                jadvali</span>
            <h2 class="text-xl font-bold text-gray-800">Institut rahbarlarining qabul kunlari</h2>
            <p class="text-sm text-gray-500 mt-2">Rahbarlar bilan shaxsiy uchrashuv uchun qabul vaqtlari</p>
        </div>

        @php
            $leaders = [
                [
                    'num' => 1,
                    'name' => "Muhiddin Po'latov Egamberdiyevich",
                    'position' => 'Rektor',
                    'days' => 'Dushanba, Seshanba, Chorshanba, Payshanba, Juma',
                    'time' => '15:00 – 18:00',
                    'color' => 'primary',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
                [
                    'num' => 2,
                    'name' => 'Berdimurodov Azizjon Shukrilloyevich',
                    'position' => "Yoshlar masalalari va ma'naviy-ma'rifiy ishlar bo'yicha birinchi prorektor",
                    'days' => 'Seshanba, Payshanba, Juma',
                    'time' => '14:00 – 18:00',
                    'color' => 'blue',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>',
                ],
                [
                    'num' => 3,
                    'name' => "Sharipov To'lqin Saidahmedovich",
                    'position' => "O'quv ishlari bo'yicha prorektor",
                    'days' => 'Seshanba, Payshanba, Juma',
                    'time' => '14:00 – 18:00',
                    'color' => 'violet',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>',
                ],
                [
                    'num' => 4,
                    'name' => 'Abdusaidov Akmal Abduvaliyevich',
                    'position' => "Ilmiy ishlar va innovatsiyalar bo'yicha prorektor v.v.b",
                    'days' => 'Seshanba, Payshanba, Shanba',
                    'time' => '14:00 – 18:00',
                    'color' => 'orange',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>',
                ],
                [
                    'num' => 5,
                    'name' => 'Ibadullayev Nurali Eshniyazovich',
                    'position' => "Moliya iqtisod ishlari bo'yicha prorektor",
                    'days' => 'Seshanba, Payshanba, Shanba',
                    'time' => '14:00 – 18:00',
                    'color' => 'emerald',
                    'icon' =>
                        '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
                ],
            ];
            $palette = [
                'primary' => [
                    'bg' => 'bg-primary-50',
                    'border' => 'border-primary-200',
                    'icon' => 'bg-primary-100 text-primary-600',
                    'badge' => 'bg-primary-600 text-white',
                    'day' => 'bg-primary-50 text-primary-700 border-primary-200',
                    'time' => 'text-primary-700',
                    'dot' => 'bg-primary-500',
                ],
                'blue' => [
                    'bg' => 'bg-blue-50',
                    'border' => 'border-blue-200',
                    'icon' => 'bg-blue-100 text-blue-600',
                    'badge' => 'bg-blue-600 text-white',
                    'day' => 'bg-blue-50 text-blue-700 border-blue-200',
                    'time' => 'text-blue-700',
                    'dot' => 'bg-blue-500',
                ],
                'violet' => [
                    'bg' => 'bg-violet-50',
                    'border' => 'border-violet-200',
                    'icon' => 'bg-violet-100 text-violet-600',
                    'badge' => 'bg-violet-600 text-white',
                    'day' => 'bg-violet-50 text-violet-700 border-violet-200',
                    'time' => 'text-violet-700',
                    'dot' => 'bg-violet-500',
                ],
                'orange' => [
                    'bg' => 'bg-orange-50',
                    'border' => 'border-orange-200',
                    'icon' => 'bg-orange-100 text-orange-600',
                    'badge' => 'bg-orange-500 text-white',
                    'day' => 'bg-orange-50 text-orange-700 border-orange-200',
                    'time' => 'text-orange-700',
                    'dot' => 'bg-orange-500',
                ],
                'emerald' => [
                    'bg' => 'bg-emerald-50',
                    'border' => 'border-emerald-200',
                    'icon' => 'bg-emerald-100 text-emerald-600',
                    'badge' => 'bg-emerald-600 text-white',
                    'day' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    'time' => 'text-emerald-700',
                    'dot' => 'bg-emerald-500',
                ],
            ];
        @endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            @foreach ($leaders as $leader)
                @php $p = $palette[$leader['color']]; @endphp
                <div
                    class="card-hover bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden
                    {{ $loop->first ? 'sm:col-span-2' : '' }}">

                    {{-- Card top accent bar --}}
                    <div class="h-1 {{ $p['dot'] }}"></div>

                    <div class="p-5">

                        {{-- Header row --}}
                        <div class="flex items-start justify-between gap-3 mb-4">
                            <div class="w-11 h-11 {{ $p['icon'] }} rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    {!! $leader['icon'] !!}
                                </svg>
                            </div>
                            <span
                                class="text-xs font-bold {{ $p['badge'] }} w-7 h-7 rounded-full flex items-center justify-center shrink-0 shadow-sm">
                                {{ $leader['num'] }}
                            </span>
                        </div>

                        {{-- Name & position --}}
                        <h3 class="font-bold text-gray-800 text-sm leading-snug mb-1">{{ $leader['name'] }}</h3>
                        <p class="text-xs text-gray-500 leading-relaxed mb-4">{{ $leader['position'] }}</p>

                        {{-- Divider --}}
                        <div class="border-t border-gray-100 mb-4"></div>

                        {{-- Days --}}
                        <div class="flex items-start gap-2 mb-3">
                            <div class="w-7 h-7 bg-gray-100 rounded-lg flex items-center justify-center shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <p class="text-xs text-gray-600 leading-relaxed">{{ $leader['days'] }}</p>
                        </div>

                        {{-- Time --}}
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 {{ $p['icon'] }} rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold {{ $p['time'] }}">{{ $leader['time'] }}</span>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
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
