@extends('layouts.app')

@section('title', 'Murojaat yuborish')

@section('content')
<div x-data="wizard()" x-cloak>

    {{-- ===== PAGE HEADING ===== --}}
    <div class="text-center mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800">Murojaat yuborish</h1>
        <p class="text-gray-500 mt-1 text-sm">Barcha maydonlarni to'ldiring. Murojaatingiz 3 ish kuni ichida ko'rib chiqiladi.</p>
    </div>

    {{-- ===== STEP INDICATOR ===== --}}
    <div class="flex items-center justify-center mb-10 select-none">
        <template x-for="(label, i) in steps" :key="i">
            <div class="flex items-center">
                {{-- Connector line --}}
                <div x-show="i > 0" class="w-12 sm:w-20 h-0.5 transition-colors duration-300"
                     :class="currentStep > i ? 'bg-primary-600' : 'bg-gray-300'"></div>

                {{-- Step circle --}}
                <div class="flex flex-col items-center gap-1">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-sm font-bold border-2 transition-all duration-300 cursor-pointer"
                         :class="{
                             'bg-primary-600 border-primary-600 text-white shadow-md':  currentStep === i + 1,
                             'bg-primary-600 border-primary-600 text-white':             currentStep > i + 1,
                             'bg-white border-gray-300 text-gray-400':                   currentStep < i + 1,
                         }"
                         @click="goToStep(i + 1)">
                        <template x-if="currentStep > i + 1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                            </svg>
                        </template>
                        <template x-if="currentStep <= i + 1">
                            <span x-text="i + 1"></span>
                        </template>
                    </div>
                    <span class="text-xs font-medium hidden sm:block transition-colors duration-300"
                          :class="currentStep >= i + 1 ? 'text-primary-600' : 'text-gray-400'"
                          x-text="label"></span>
                </div>
            </div>
        </template>
    </div>

    {{-- ===== FORM ===== --}}
    <form method="POST" action="{{ route('appeals.store') }}" enctype="multipart/form-data" id="appealForm">
        @csrf

        {{-- Hidden field carried from step 1 --}}
        <input type="hidden" name="category_id" x-model="selectedCategory">

        {{-- =================== STEP 1: CATEGORY =================== --}}
        <div x-show="currentStep === 1" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Murojaat turini tanlang</h2>
                <p class="text-sm text-gray-400 mb-6">Murojaatingizga mos keladigan kategoriyani belgilang.</p>

                @if($categories->isEmpty())
                    <div class="text-center py-10 text-gray-400">
                        <svg class="w-12 h-12 mx-auto mb-3 opacity-40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p>Kategoriyalar mavjud emas.</p>
                    </div>
                @else
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($categories as $category)
                            <button type="button"
                                    @click="selectCategory({{ $category->id }})"
                                    :class="selectedCategory == {{ $category->id }}
                                        ? 'border-primary-500 bg-primary-50 shadow-md ring-2 ring-primary-300'
                                        : 'border-gray-200 bg-white hover:border-primary-300 hover:bg-gray-50'"
                                    class="relative flex flex-col items-center gap-3 p-5 rounded-xl border-2 transition-all duration-200 text-center group cursor-pointer">

                                {{-- Check badge --}}
                                <div x-show="selectedCategory == {{ $category->id }}"
                                     class="absolute top-2 right-2 w-5 h-5 bg-primary-500 rounded-full flex items-center justify-center">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>

                                {{-- Icon --}}
                                <span class="text-3xl leading-none">{{ $category->icon ?? '📋' }}</span>

                                {{-- Name --}}
                                <span class="text-sm font-medium text-gray-700 group-hover:text-primary-700 leading-tight"
                                      :class="selectedCategory == {{ $category->id }} ? 'text-primary-700' : ''">
                                    {{ $category->name_uz }}
                                </span>
                            </button>
                        @endforeach
                    </div>

                    @error('category_id')
                        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                @endif
            </div>

            <div class="flex justify-end mt-6">
                <button type="button" @click="nextStep()"
                        :disabled="!selectedCategory"
                        :class="selectedCategory ? 'bg-primary-600 hover:bg-primary-700 cursor-pointer' : 'bg-gray-300 cursor-not-allowed'"
                        class="px-8 py-3 rounded-xl text-white font-semibold transition-colors duration-200 flex items-center gap-2">
                    Davom etish
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- =================== STEP 2: PERSONAL INFO =================== --}}
        <div x-show="currentStep === 2" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Shaxsiy ma'lumotlar va murojaat matni</h2>
                <p class="text-sm text-gray-400 mb-6">Aloqa ma'lumotlaringizni to'g'ri kiriting.</p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    {{-- Full name --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            To'liq ism-familiya <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="full_name" value="{{ old('full_name') }}"
                               placeholder="Surname Name Patronymic"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition @error('full_name') border-red-400 bg-red-50 @enderror">
                        @error('full_name')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Telefon raqami <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="phone" value="{{ old('phone', '+998') }}"
                               placeholder="+998901234567"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition @error('phone') border-red-400 bg-red-50 @enderror">
                        @error('phone')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Elektron pochta
                            <span class="text-gray-400 font-normal">(ixtiyoriy)</span>
                        </label>
                        <input type="email" name="email" value="{{ old('email') }}"
                               placeholder="example@email.com"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition @error('email') border-red-400 bg-red-50 @enderror">
                        @error('email')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Subject --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Murojaat mavzusi <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="subject" value="{{ old('subject') }}"
                               placeholder="Qisqacha mavzuni kiriting"
                               class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition @error('subject') border-red-400 bg-red-50 @enderror">
                        @error('subject')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Body --}}
                    <div class="sm:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">
                            Murojaat matni <span class="text-red-500">*</span>
                        </label>
                        <textarea name="body" rows="6"
                                  x-data="{ count: {{ strlen(old('body', '')) }} }"
                                  @input="count = $el.value.length"
                                  placeholder="Murojaatingizni batafsil yozing (kamida 20 ta belgi)..."
                                  class="w-full border border-gray-300 rounded-lg px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-400 focus:border-transparent transition resize-none @error('body') border-red-400 bg-red-50 @enderror">{{ old('body') }}</textarea>
                        <div class="flex justify-between mt-1">
                            @error('body')
                                <p class="text-xs text-red-600">{{ $message }}</p>
                            @else
                                <span></span>
                            @enderror
                            <span class="text-xs text-gray-400" x-text="count + ' / 2000'"></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" @click="prevStep()"
                        class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-medium hover:bg-gray-100 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Orqaga
                </button>
                <button type="button" @click="nextStep()"
                        class="px-8 py-3 rounded-xl bg-primary-600 hover:bg-primary-700 text-white font-semibold transition flex items-center gap-2">
                    Davom etish
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- =================== STEP 3: FILES & CONFIRM =================== --}}
        <div x-show="currentStep === 3" x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">

            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 sm:p-8">
                <h2 class="text-lg font-semibold text-gray-700 mb-1">Fayllar va tasdiqlash</h2>
                <p class="text-sm text-gray-400 mb-6">Hujjatlarni ilovaga qo'shing (ixtiyoriy) va ma'lumotlarni tasdiqlang.</p>

                {{-- File drop zone --}}
                <div x-data="fileUpload()" class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Ilovalar <span class="text-gray-400 font-normal">(PDF, JPG, PNG, DOCX — har biri max 5MB, jami 5 ta)</span>
                    </label>

                    <div @dragover.prevent="dragging = true" @dragleave.prevent="dragging = false"
                         @drop.prevent="handleDrop($event)"
                         :class="dragging ? 'border-primary-500 bg-primary-50' : 'border-gray-300 bg-gray-50 hover:bg-gray-100'"
                         class="border-2 border-dashed rounded-xl p-8 text-center transition-colors duration-200 cursor-pointer"
                         @click="$refs.fileInput.click()">
                        <svg class="w-10 h-10 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-sm text-gray-600 font-medium">Fayllarni bu yerga sudrab tashlang</p>
                        <p class="text-xs text-gray-400 mt-1">yoki bosing va tanlang</p>
                        <input type="file" name="files[]" multiple accept=".pdf,.jpg,.jpeg,.png,.docx"
                               x-ref="fileInput" class="hidden" @change="handleFiles($event)">
                    </div>

                    {{-- File list --}}
                    <ul x-show="files.length > 0" class="mt-3 space-y-2">
                        <template x-for="(file, index) in files" :key="index">
                            <li class="flex items-center justify-between bg-primary-50 border border-primary-100 rounded-lg px-4 py-2 text-sm">
                                <div class="flex items-center gap-2 min-w-0">
                                    <svg class="w-4 h-4 text-primary-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <span class="truncate text-gray-700" x-text="file.name"></span>
                                    <span class="text-gray-400 shrink-0" x-text="formatSize(file.size)"></span>
                                </div>
                                <button type="button" @click="removeFile(index)"
                                        class="ml-3 text-red-400 hover:text-red-600 shrink-0 transition">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </li>
                        </template>
                    </ul>

                    <p x-show="fileError" x-text="fileError" class="mt-2 text-xs text-red-600"></p>
                    @error('files') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                    @error('files.*') <p class="mt-2 text-xs text-red-600">{{ $message }}</p> @enderror
                </div>

                {{-- Confirmation notice --}}
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex gap-3">
                    <svg class="w-5 h-5 text-amber-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-amber-800 leading-relaxed">
                        Murojaatingizni yuborishdan oldin barcha ma'lumotlar to'g'riligini tekshiring.
                        Murojaat yuborilgandan so'ng tracking kodi beriladi.
                    </p>
                </div>
            </div>

            <div class="flex justify-between mt-6">
                <button type="button" @click="prevStep()"
                        class="px-6 py-3 rounded-xl border border-gray-300 text-gray-600 font-medium hover:bg-gray-100 transition flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                    Orqaga
                </button>

                <button type="submit" :disabled="submitting"
                        @click="submitting = true"
                        :class="submitting ? 'opacity-60 cursor-wait' : 'hover:bg-primary-700'"
                        class="px-8 py-3 rounded-xl bg-primary-600 text-white font-semibold transition flex items-center gap-2 shadow-md">
                    <svg x-show="submitting" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"/>
                    </svg>
                    <svg x-show="!submitting" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                    </svg>
                    <span x-text="submitting ? 'Yuborilmoqda...' : 'Murojaatni yuborish'"></span>
                </button>
            </div>
        </div>

    </form>
</div>
@endsection

@push('scripts')
<script>
function wizard() {
    return {
        currentStep: {{ $errors->any() ? 2 : 1 }},
        steps: ['Kategoriya', 'Ma\'lumotlar', 'Tasdiqlash'],
        selectedCategory: '{{ old('category_id', '') }}',
        submitting: false,

        selectCategory(id) {
            this.selectedCategory = id;
        },

        goToStep(step) {
            if (step < this.currentStep) this.currentStep = step;
        },

        nextStep() {
            if (this.currentStep === 1 && !this.selectedCategory) return;
            if (this.currentStep < this.steps.length) this.currentStep++;
        },

        prevStep() {
            if (this.currentStep > 1) this.currentStep--;
        },
    };
}

function fileUpload() {
    return {
        files: [],
        dragging: false,
        fileError: '',
        allowedTypes: ['application/pdf', 'image/jpeg', 'image/png',
                       'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        maxSize: 5 * 1024 * 1024,

        handleFiles(event) {
            this.addFiles(Array.from(event.target.files));
        },

        handleDrop(event) {
            this.dragging = false;
            this.addFiles(Array.from(event.dataTransfer.files));
        },

        addFiles(incoming) {
            this.fileError = '';
            for (const file of incoming) {
                if (this.files.length >= 5) {
                    this.fileError = 'Maksimal 5 ta fayl yuklash mumkin.'; break;
                }
                if (file.size > this.maxSize) {
                    this.fileError = `"${file.name}" fayli 5MB dan katta.`; break;
                }
                if (!this.allowedTypes.includes(file.type)) {
                    this.fileError = `"${file.name}" — ruxsat etilmagan fayl turi.`; break;
                }
                this.files.push(file);
            }
            this.syncInput();
        },

        removeFile(index) {
            this.files.splice(index, 1);
            this.fileError = '';
            this.syncInput();
        },

        syncInput() {
            const input = this.$refs.fileInput;
            const dt = new DataTransfer();
            this.files.forEach(f => dt.items.add(f));
            input.files = dt.files;
        },

        formatSize(bytes) {
            return bytes < 1024 * 1024
                ? (bytes / 1024).toFixed(0) + ' KB'
                : (bytes / 1024 / 1024).toFixed(1) + ' MB';
        },
    };
}
</script>
@endpush
