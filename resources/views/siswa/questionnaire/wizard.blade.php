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
    <style>
        body { font-family: 'Inter', sans-serif; }
        .option-card input[type="radio"]:checked ~ .option-label {
            border-color: #7c3aed;
            background-color: #7c3aed;
            color: white;
        }
        .option-card input[type="radio"]:checked ~ .option-label .option-check {
            display: flex;
        }
        .option-card input[type="radio"]:checked ~ .option-label .option-num {
            display: none;
        }
        .slide-in {
            animation: slideIn .35s cubic-bezier(.4,0,.2,1);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateX(30px); }
            to   { opacity: 1; transform: translateX(0); }
        }
    </style>
</head>
<body class="h-full bg-slate-50 font-inter">

<div class="min-h-screen flex flex-col">

    {{-- Top Progress Bar --}}
    <div class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-slate-100 shadow-sm">
        {{-- Instrument step indicator --}}
        <div class="flex items-center px-4 sm:px-6 py-2.5">
            <a href="{{ $prev_route }}"
               class="flex items-center gap-1.5 text-slate-500 hover:text-slate-800 text-sm font-medium transition mr-4 flex-shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali
            </a>

            {{-- Instrument tabs --}}
            <div class="flex items-center gap-1 flex-1 justify-center">
                @php
                    $instruments = [
                        ['key'=>'SDQ',   'n'=>25, 'active'=>$instrument_key==='sdq'],
                        ['key'=>'PSC-17','n'=>17, 'active'=>$instrument_key==='psc17'],
                        ['key'=>'SAS-SV','n'=>10, 'active'=>$instrument_key==='sassv'],
                    ];
                @endphp
                @foreach($instruments as $idx => $inst)
                    <div class="flex items-center gap-1">
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold {{ $inst['active'] ? 'bg-violet-600 text-white' : 'bg-slate-100 text-slate-400' }}">
                            <span>{{ $inst['key'] }}</span>
                        </div>
                        @if($idx < 2)
                            <div class="w-4 h-px bg-slate-200 mx-0.5"></div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Counter --}}
            <span class="flex-shrink-0 ml-4 text-xs text-slate-500 font-medium whitespace-nowrap">
                {{ $step }}/{{ $total }}
            </span>
        </div>

        {{-- Progress bar --}}
        <div class="h-1.5 bg-slate-100">
            <div class="h-full bg-gradient-to-r from-violet-600 to-indigo-500 transition-all duration-500 ease-out"
                 style="width: {{ round(($step / $total) * 100) }}%"></div>
        </div>
    </div>

    {{-- Question area --}}
    <div class="flex-1 flex items-center justify-center px-4 py-24">
        <div class="w-full max-w-xl slide-in">

            {{-- Autosave badge --}}
            <div class="flex items-center justify-center gap-1.5 text-xs text-emerald-600 font-medium mb-6">
                <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                Autosave aktif
            </div>

            {{-- Question number + instrument badge --}}
            <div class="flex items-center gap-2 mb-4">
                <span class="bg-violet-100 text-violet-700 text-xs font-bold px-3 py-1 rounded-full">{{ $instrument }}</span>
                <span class="text-slate-400 text-xs">Pertanyaan {{ $step }} dari {{ $total }}</span>
            </div>

            {{-- Question text --}}
            <h2 class="text-xl sm:text-2xl font-bold text-slate-800 leading-snug mb-8">
                {{ $question->text }}
            </h2>

            {{-- Errors --}}
            @if($errors->has('answer'))
                <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-xl mb-4">
                    <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ $errors->first('answer') }}
                </div>
            @endif

            {{-- Answer form --}}
            <form method="POST" action="{{ route('siswa.questionnaire.'.$instrument_key.'.store') }}" id="wizardForm">
                @csrf
                <input type="hidden" name="step" value="{{ $step }}">

                <div class="space-y-3 mb-8">
                    @foreach($options as $value => $label)
                        <label class="option-card flex cursor-pointer">
                            <input type="radio" name="answer" value="{{ $value }}"
                                   class="sr-only"
                                   {{ (isset($saved[$step]) && $saved[$step] == $value) ? 'checked' : '' }}
                                   onchange="document.getElementById('wizardForm').submit()">
                            <div class="option-label flex items-center gap-4 w-full border-2 border-slate-200 bg-white rounded-2xl px-5 py-4 transition-all duration-200 hover:border-violet-300 hover:bg-violet-50">
                                {{-- Number --}}
                                <span class="option-num w-8 h-8 rounded-full bg-slate-100 text-slate-500 text-sm font-bold flex items-center justify-center flex-shrink-0">
                                    {{ $loop->iteration }}
                                </span>
                                {{-- Check icon (hidden until selected) --}}
                                <span class="option-check hidden w-8 h-8 rounded-full bg-white/30 items-center justify-center flex-shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </span>
                                <span class="text-sm font-medium leading-snug">{{ $label }}</span>
                            </div>
                        </label>
                    @endforeach
                </div>

                {{-- Manual next button (fallback jika JS off) --}}
                <button type="submit"
                        class="w-full bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-bold py-4 rounded-2xl text-base shadow-lg shadow-violet-200 hover:shadow-xl active:scale-[.98] transition-all duration-200">
                    {{ $next_step ? 'Lanjut →' : 'Selesai & Lihat Hasil 🎉' }}
                </button>
            </form>

            {{-- Dots indicator --}}
            <div class="flex items-center justify-center gap-1 mt-6 flex-wrap">
                @for($i = 1; $i <= $total; $i++)
                    <div class="rounded-full transition-all duration-300
                        {{ $i == $step ? 'w-4 h-2 bg-violet-600' : (isset($saved[$i]) ? 'w-2 h-2 bg-emerald-400' : 'w-2 h-2 bg-slate-200') }}">
                    </div>
                @endfor
            </div>
            <p class="text-center text-xs text-slate-400 mt-2">
                {{ count($saved) }} dari {{ $total }} soal dijawab
            </p>
        </div>
    </div>
</div>

</body>
</html>
