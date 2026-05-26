<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Masuk – EduCare</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .wave-bg { background: linear-gradient(135deg, #7c3aed 0%, #4f46e5 50%, #0ea5e9 100%); }
        .float-card {
            animation: floatCard 6s ease-in-out infinite;
        }
        @keyframes floatCard {
            0%,100% { transform: translateY(0px); }
            50%      { transform: translateY(-8px); }
        }
        .input-focus { transition: all .2s; }
        .input-focus:focus { box-shadow: 0 0 0 3px rgba(124,58,237,.15); }
    </style>
</head>
<body class="h-full bg-slate-50">

<div class="min-h-screen flex">

    {{-- Left Panel – Branding --}}
    <div class="hidden lg:flex lg:w-1/2 wave-bg relative overflow-hidden flex-col items-center justify-center px-12 py-10">

        {{-- Decorative circles --}}
        <div class="absolute top-[-80px] left-[-80px] w-80 h-80 rounded-full bg-white/5"></div>
        <div class="absolute bottom-[-60px] right-[-60px] w-64 h-64 rounded-full bg-white/5"></div>
        <div class="absolute top-1/2 left-0 w-2 h-32 bg-white/10 rounded-r-full"></div>

        {{-- Floating info card --}}
        <div class="float-card bg-white/15 backdrop-blur-md border border-white/20 rounded-2xl p-6 mb-8 w-full max-w-sm shadow-2xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-white font-bold text-sm">EduCare</p>
                    <p class="text-white/60 text-xs">Monitoring Psikososial</p>
                </div>
            </div>
            <div class="space-y-3">
                @foreach(['SDQ – Kesehatan Mental & Perilaku', 'PSC-17 – Gejala Psikososial', 'SAS-SV – Adiksi Smartphone'] as $item)
                    <div class="flex items-center gap-2 text-white/80 text-sm">
                        <svg class="w-4 h-4 text-emerald-300 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $item }}
                    </div>
                @endforeach
            </div>
        </div>

        <h1 class="text-white font-extrabold text-3xl text-center leading-tight mb-3">
            Kenali Kesehatan<br>Psikososialmu
        </h1>
        <p class="text-white/70 text-center text-sm max-w-xs leading-relaxed">
            Sistem berbasis AI untuk memantau dan menganalisis risiko psikososial remaja berdasarkan pola penggunaan gadget.
        </p>

        {{-- Stats --}}
        <div class="flex gap-6 mt-8">
            @foreach([['3', 'Instrumen'], ['52', 'Pertanyaan'], ['4', 'Level Risiko']] as [$num, $label])
                <div class="text-center">
                    <p class="text-white font-bold text-2xl">{{ $num }}</p>
                    <p class="text-white/60 text-xs">{{ $label }}</p>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Right Panel – Login Form --}}
    <div class="flex-1 flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md">

            {{-- Mobile logo --}}
            <div class="lg:hidden flex items-center gap-3 mb-8">
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center shadow-lg">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                              d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                    </svg>
                </div>
                <p class="font-bold text-slate-800">EduCare</p>
            </div>

            <h2 class="text-2xl font-bold text-slate-800 mb-1">Selamat Datang 👋</h2>
            <p class="text-slate-500 text-sm mb-8">Masuk ke akun siswa kamu untuk melanjutkan</p>

            {{-- Status --}}
            @if(session('status'))
                <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5" for="email">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}"
                           required autofocus autocomplete="email"
                           placeholder="siswa@sekolah.sch.id"
                           class="input-focus w-full px-4 py-3 bg-white border {{ $errors->has('email') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:border-violet-400">
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="text-sm font-medium text-slate-700" for="password">Password</label>
                        @if(Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-violet-600 hover:text-violet-800 font-medium">Lupa password?</a>
                        @endif
                    </div>
                    <input id="password" type="password" name="password"
                           required autocomplete="current-password"
                           placeholder="••••••••"
                           class="input-focus w-full px-4 py-3 bg-white border {{ $errors->has('password') ? 'border-red-400' : 'border-slate-200' }} rounded-xl text-slate-800 text-sm placeholder-slate-400 focus:outline-none focus:border-violet-400">
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-2">
                    <input id="remember" type="checkbox" name="remember"
                           class="w-4 h-4 rounded border-slate-300 text-violet-600 focus:ring-violet-500">
                    <label for="remember" class="text-sm text-slate-600">Ingat saya</label>
                </div>

                <button type="submit"
                        class="w-full bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-semibold py-3 px-4 rounded-xl text-sm hover:shadow-lg hover:shadow-violet-200 active:scale-[.98] transition-all duration-200 flex items-center justify-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    Masuk ke Akun
                </button>

                {{-- Demo hint --}}
                <div class="mt-4 bg-slate-50 rounded-xl p-4 border border-slate-100">
                    <p class="text-xs font-semibold text-slate-500 mb-2">🔑 Akun Demo Siswa:</p>
                    <div class="space-y-1 text-xs text-slate-500">
                        <p>Email: <span class="font-mono text-slate-700">andi@siswa.test</span></p>
                        <p>Password: <span class="font-mono text-slate-700">password</span></p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
