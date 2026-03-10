@extends('layouts.app')

@section('title', 'Murojaat qabul qilindi')

@section('content')
    <div class="max-w-xl mx-auto text-center py-8">

        {{-- Success icon --}}
        <div class="w-20 h-20 bg-primary-100 rounded-full flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-10 h-10 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
        </div>

        <h1 class="text-2xl font-bold text-gray-800 mb-2">Murojaat muvaffaqiyatli yuborildi!</h1>
        <p class="text-gray-500 mb-8">Murojaatingiz qabul qilindi. Tracking kodi orqali holat kuzating.</p>

        {{-- Tracking code box --}}
        <div class="bg-white border-2 border-primary-200 rounded-2xl p-6 shadow-sm mb-8">
            <p class="text-sm text-gray-500 mb-2">Sizning tracking kodingiz:</p>
            <div class="flex items-center justify-center gap-3">
                <span class="text-3xl font-bold tracking-widest text-primary-700 font-mono">
                    {{ $tracking_code }}
                </span>
                <button onclick="navigator.clipboard.writeText('{{ $tracking_code }}'); this.textContent='✓'"
                    class="text-xs bg-primary-100 hover:bg-primary-200 text-primary-700 font-medium px-3 py-1.5 rounded-lg transition">
                    Nusxa
                </button>
            </div>
            <div class="flex items-start gap-2 mt-4 bg-amber-50 border border-amber-300 rounded-xl px-4 py-3 text-left">
                <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
                </svg>
                <p class="text-sm text-amber-800 font-medium">
                    Ushbu tracking kodini albatta saqlab qo'ying! Murojaat holati faqat shu kod orqali tekshiriladi. Kod yo'qolsa, murojaatingizni kuzatib bo'lmaydi.
                </p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('appeals.show', $tracking_code) }}"
                class="px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition shadow-md">
                Murojaatni kuzatish
            </a>
            <a href="{{ route('appeals.index') }}"
                class="px-6 py-3 border border-gray-300 text-gray-600 font-medium rounded-xl hover:bg-gray-50 transition">
                Yangi murojaat
            </a>
        </div>
    </div>
@endsection
