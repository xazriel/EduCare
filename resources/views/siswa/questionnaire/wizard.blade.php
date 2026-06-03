<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $instrument }} – Assessment EduCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @php
        $theme = [
            'sdq' => [
                'bg' => 'bg-violet-100 text-violet-700',
                'border' => 'border-violet-200',
                'accent' => '#7c3aed', // violet-600
                'hover_bg' => 'hover:bg-violet-50/50 hover:border-violet-300',
                'gradient' => 'from-violet-600 to-indigo-600',
                'shadow' => 'shadow-violet-200 hover:shadow-violet-300',
                'shadow_color' => 'rgba(124, 58, 237, 0.15)',
            ],
            'psc17' => [
                'bg' => 'bg-emerald-100 text-emerald-700',
                'border' => 'border-emerald-200',
                'accent' => '#059669', // emerald-600
                'hover_bg' => 'hover:bg-emerald-50/50 hover:border-emerald-300',
                'gradient' => 'from-emerald-600 to-teal-600',
                'shadow' => 'shadow-emerald-200 hover:shadow-emerald-300',
                'shadow_color' => 'rgba(5, 150, 105, 0.15)',
            ],
            'sassv' => [
                'bg' => 'bg-amber-100 text-amber-700',
                'border' => 'border-amber-200',
                'accent' => '#d97706', // amber-600
                'hover_bg' => 'hover:bg-amber-50/50 hover:border-amber-300',
                'gradient' => 'from-amber-500 to-orange-600',
                'shadow' => 'shadow-amber-200 hover:shadow-amber-300',
                'shadow_color' => 'rgba(217, 119, 6, 0.15)',
            ],
        ][$instrument_key] ?? [
            'bg' => 'bg-violet-100 text-violet-700',
            'border' => 'border-violet-200',
            'accent' => '#7c3aed',
            'hover_bg' => 'hover:bg-violet-50/50 hover:border-violet-300',
            'gradient' => 'from-violet-600 to-indigo-600',
            'shadow' => 'shadow-violet-200 hover:shadow-violet-300',
            'shadow_color' => 'rgba(124, 58, 237, 0.15)',
        ];
        
        $saved = old('answers', $saved);
        $gridCols = count($options) == 6 ? 'grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6' : 'grid-cols-1 sm:grid-cols-3';
    @endphp
    <style>
        body { font-family: 'Inter', sans-serif; }
        
        /* Custom radio buttons styles */
        .option-radio input[type="radio"]:checked + .option-box {
            background-color: {{ $theme['accent'] }};
            border-color: {{ $theme['accent'] }};
            color: white;
            box-shadow: 0 6px 15px {{ $theme['shadow_color'] }};
            transform: translateY(-2px);
        }
        
        .option-box {
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .option-radio:hover .option-box {
            transform: translateY(-1px);
            border-color: #cbd5e1;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }

        .slide-in {
            animation: slideIn .35s cubic-bezier(.4,0,.2,1);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(15px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="h-full bg-slate-50 font-inter">

<div class="min-h-screen flex flex-col">

    {{-- Top Progress Bar --}}
    <div class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-100 shadow-sm">
        {{-- Instrument step indicator --}}
        <div class="flex items-center px-4 sm:px-6 py-2.5">
            <button type="button" onclick="submitToTab('back')"
               class="flex items-center gap-1.5 text-slate-500 hover:text-slate-800 text-sm font-medium transition mr-4 flex-shrink-0 outline-none cursor-pointer">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </button>

            {{-- Instrument tabs --}}
            <div class="flex items-center gap-1.5 flex-1 justify-center">
                @php
                    $instruments = [
                        ['key'=>'sdq',   'label'=>'SDQ',   'active'=>$instrument_key==='sdq',  'color'=>'bg-violet-600 hover:bg-violet-700'],
                        ['key'=>'psc17', 'label'=>'PSC-17', 'active'=>$instrument_key==='psc17','color'=>'bg-emerald-600 hover:bg-emerald-700'],
                        ['key'=>'sassv', 'label'=>'SAS-SV', 'active'=>$instrument_key==='sassv','color'=>'bg-amber-500 hover:bg-amber-600'],
                    ];
                @endphp
                @foreach($instruments as $idx => $inst)
                    <div class="flex items-center gap-1">
                        <button type="button" onclick="submitToTab('{{ $inst['key'] }}')"
                                class="flex items-center gap-1.5 px-3 py-1.5 rounded-full text-xs font-semibold transition cursor-pointer outline-none {{ $inst['active'] ? $inst['color'] . ' text-white shadow-sm' : 'bg-slate-100 text-slate-500 hover:bg-slate-200' }}">
                            <span>{{ $inst['label'] }}</span>
                        </button>
                        @if($idx < 2)
                            <div class="w-4 h-px bg-slate-200 mx-0.5"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Counter --}}
            <span class="flex-shrink-0 ml-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                Instrumen {{ $step }}/{{ $total }}
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="h-1.5 bg-slate-100">
            <div class="h-full bg-gradient-to-r {{ $theme['gradient'] }} transition-all duration-500 ease-out"
                 style="width: {{ round(($step / $total) * 100) }}%"></div>
        </div>
    </div>

    {{-- Question area --}}
    <div class="flex-1 flex justify-center px-4 py-24">
        <div class="w-full max-w-3xl slide-in">

            {{-- Autosave badge --}}
            <div class="flex items-center justify-center gap-1.5 text-xs text-emerald-600 font-medium mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Pindah tab akan otomatis menyimpan draf jawaban kuesioner Anda
            </div>

            {{-- Header Card --}}
            <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 mb-6">
                <div class="flex items-center gap-2 mb-3">
                    <span class="{{ $theme['bg'] }} text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider">{{ $instrument }}</span>
                    <span class="text-slate-400 text-xs">{{ count($questions) }} Pertanyaan Total</span>
                </div>
                <h2 class="text-lg font-bold text-slate-800 leading-snug">
                    @if($instrument_key === 'sdq')
                        Kuesioner Kekuatan dan Kesulitan Perilaku (SDQ)
                    @elseif($instrument_key === 'psc17')
                        Pemeriksaan Gejala Psikososial (PSC-17)
                    @else
                        Skala Kecanduan Smartphone (SAS-SV)
                    @endif
                </h2>
                <p class="text-slate-500 text-xs mt-1 leading-relaxed">
                    @if($instrument_key === 'sdq')
                        Untuk setiap pertanyaan, pilih opsi yang paling menggambarkan kondisi dirimu selama 6 bulan terakhir.
                    @elseif($instrument_key === 'psc17')
                        Pilih seberapa sering kamu merasakan kondisi berikut dalam kehidupan sehari-harimu.
                    @else
                        Pilih seberapa jauh kamu menyetujui pernyataan berikut mengenai perilaku penggunaan smartphone-mu.
                    @endif
                </p>
            </div>

            {{-- Errors Notification --}}
            @if($errors->has('answers'))
                <div class="flex items-start gap-2 bg-rose-50 border border-rose-200 text-rose-700 text-xs px-4 py-3 rounded-xl mb-6 shadow-sm">
                    <svg class="w-4 h-4 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <span class="font-bold">Pengisian Belum Lengkap:</span>
                        <p class="mt-0.5 leading-relaxed">{{ $errors->first('answers') }}</p>
                    </div>
                </div>
            @endif

            {{-- Answer form --}}
            <form method="POST" action="{{ route('siswa.questionnaire.'.$instrument_key.'.store') }}" id="wizardForm" class="space-y-6">
                @csrf
                <input type="hidden" name="next_tab" id="nextTabInput" value="">

                <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-2 sm:p-4 divide-y divide-slate-100">
                    @foreach($questions as $q)
                        @php
                            $isMissed = $errors->has('answers') && !isset($saved[$q->number]);
                        @endphp
                        <div class="py-5 px-3 sm:px-4 rounded-2xl transition duration-200 {{ $isMissed ? 'bg-rose-50/50 border-2 border-rose-200 my-2' : '' }}">
                            <div class="flex items-start justify-between gap-4 mb-4">
                                <p class="text-sm font-semibold text-slate-800 leading-relaxed">
                                    {{ $q->number }}. {{ $q->text }}
                                </p>
                                @if($isMissed)
                                    <span class="flex-shrink-0 bg-rose-100 text-rose-700 text-[9px] font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider animate-pulse">Wajib diisi</span>
                                @endif
                            </div>

                            {{-- Options Grid --}}
                            <div class="grid {{ $gridCols }} gap-3 sm:gap-4">
                                @foreach($options as $value => $label)
                                    <label class="option-radio cursor-pointer">
                                        <input type="radio" name="answers[{{ $q->number }}]" value="{{ $value }}"
                                               class="sr-only"
                                               {{ (isset($saved[$q->number]) && $saved[$q->number] == $value) ? 'checked' : '' }}>
                                        <div class="option-box px-4 py-3.5 text-center text-xs sm:text-sm font-semibold text-slate-600 border border-slate-200 rounded-xl">
                                            {{ $label }}
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Action button --}}
                <div class="pt-2">
                    <button type="submit"
                            class="w-full bg-gradient-to-r {{ $theme['gradient'] }} text-white font-bold py-4 rounded-2xl text-base shadow-lg {{ $theme['shadow'] }} transition-all duration-200 hover:-translate-y-0.5 hover:shadow-xl active:scale-[.98] cursor-pointer">
                        {{ $step < 3 ? 'Simpan & Lanjut ke Instrumen Berikutnya →' : 'Selesai & Lihat Hasil 🎉' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function submitToTab(tabKey) {
        const form = document.getElementById('wizardForm');
        const nextTabInput = document.getElementById('nextTabInput');
        if (form && nextTabInput) {
            nextTabInput.value = tabKey;
            form.submit();
        } else {
            // Fallback
            let url = '/siswa/assessment';
            if (tabKey === 'sdq') url += '/sdq';
            else if (tabKey === 'psc17') url += '/psc17';
            else if (tabKey === 'sassv') url += '/sassv';
            window.location.href = url;
        }
    }
</script>

</body>
</html>
