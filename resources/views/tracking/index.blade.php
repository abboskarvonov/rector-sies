@extends('layouts.app')

@section('title', 'Murojaatni kuzatish')

@section('content')
    <div class="max-w-lg mx-auto py-6">

        {{-- Icon + heading --}}
        <div class="text-center mb-8">
            <div class="w-16 h-16 bg-primary-100 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-inner">
                <svg class="w-8 h-8 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                        d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z" />
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-800">Murojaatni kuzatish</h1>
            <p class="text-gray-500 text-sm mt-1">
                Tracking kodni kiriting va murojaatingiz holatini bilib oling.
            </p>
        </div>

        {{-- Form card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-7">
            <form method="POST" action="{{ route('tracking.track') }}">
                @csrf

                <label for="tracking_code" class="block text-sm font-medium text-gray-700 mb-2">
                    Tracking kodi
                </label>

                <div class="flex gap-3">
                    <div class="flex-1">
                        <input id="tracking_code" type="text" name="tracking_code" value="{{ old('tracking_code') }}"
                            placeholder="APP-2025-00001" autocomplete="off" autofocus
                            class="w-full border border-gray-300 rounded-xl px-4 py-3 text-sm font-mono tracking-wider uppercase
                               focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition
                               @error('tracking_code') border-red-400 bg-red-50 @enderror"
                            oninput="this.value = this.value.toUpperCase()">
                    </div>
                    <button type="submit"
                        class="px-5 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl
                               transition shadow-sm flex items-center gap-2 shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-4.35-4.35M17 11A6 6 0 111 11a6 6 0 0116 0z" />
                        </svg>
                        <span class="hidden sm:inline">Tekshirish</span>
                    </button>
                </div>

                @error('tracking_code')
                    <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror

                <p class="mt-3 text-xs text-gray-400">
                    Format: <span class="font-mono text-gray-500">APP-YYYY-NNNNN</span>
                    &nbsp;—&nbsp; masalan, <span class="font-mono text-gray-500">APP-2025-00142</span>
                </p>
            </form>
        </div>

        {{-- Appeal not found message --}}
        @if (session('not_found'))
            <div class="mt-4 bg-red-50 border border-red-200 rounded-xl p-4 flex gap-3 items-start">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-red-700">Murojaat topilmadi</p>
                    <p class="text-xs text-red-500 mt-0.5">
                        <strong>{{ session('not_found') }}</strong> kodi bo'yicha murojaat topilmadi.
                        Kodni tekshirib qayta urinib ko'ring.
                    </p>
                </div>
            </div>
        @endif

        {{-- Hint --}}
        <div class="mt-6 bg-primary-50 border border-primary-100 rounded-xl p-4 flex gap-3 items-start">
            <svg class="w-5 h-5 text-primary-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <p class="text-sm text-primary-700">
                Tracking kodi murojaat yuborilgandan keyin ekranda ko'rsatiladi.
            </p>
        </div>

        <div class="text-center mt-6">
            <p class="text-sm text-gray-500">
                Hali murojaat yubormaganmisiz?
                <a href="{{ route('appeals.index') }}" class="text-primary-600 font-medium hover:underline">
                    Murojaat yuborish →
                </a>
            </p>
        </div>

    </div>
@endsection
