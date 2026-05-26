@props(['pageTitle' => 'Dashboard', 'pageSubtitle' => null])

<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $pageTitle }} – EduCare</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="h-full bg-slate-50 font-inter antialiased" x-data="{ sidebarOpen: false }">

{{-- Mobile overlay --}}
<div x-show="sidebarOpen"
     x-transition.opacity
     @click="sidebarOpen = false"
     class="fixed inset-0 z-20 bg-black/40 backdrop-blur-sm lg:hidden"
     style="display:none"></div>

{{-- Sidebar --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-30 w-64 bg-white shadow-xl flex flex-col transition-transform duration-300 lg:translate-x-0">

    {{-- Logo --}}
    <div class="flex items-center gap-3 px-6 py-5 border-b border-slate-100">
        <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-violet-600 to-indigo-600 flex items-center justify-center shadow-lg shadow-violet-200">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                      d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
            </svg>
        </div>
        <div>
            <p class="font-bold text-slate-800 text-sm leading-none">EduCare</p>
            <p class="text-[10px] text-slate-400 mt-0.5">Psikososial Remaja</p>
        </div>
    </div>

    {{-- User info --}}
    <div class="px-4 py-4 border-b border-slate-100">
        <div class="flex items-center gap-3 bg-violet-50 rounded-xl p-3">
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-violet-500 to-pink-500 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-xs font-semibold text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-500 truncate">{{ Auth::user()->nis ?? 'Siswa' }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto">
        @php
            $navItems = [
                ['route' => 'siswa.dashboard',           'label' => 'Dashboard',  'match' => 'siswa.dashboard',
                 'icon'  => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
                ['route' => 'siswa.questionnaire.index', 'label' => 'Assessment', 'match' => 'siswa.questionnaire.*',
                 'icon'  => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01'],
                ['route' => 'siswa.riwayat.index',       'label' => 'Riwayat',   'match' => 'siswa.riwayat.*',
                 'icon'  => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
                ['route' => 'siswa.chatbot.index',       'label' => 'EduBot',    'match' => 'siswa.chatbot.*',
                 'icon'  => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z'],
            ];
        @endphp

        @foreach($navItems as $item)
            @php $active = request()->routeIs($item['match']); @endphp
            <a href="{{ route($item['route']) }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-medium transition-all duration-200
                      {{ $active ? 'bg-violet-600 text-white shadow-md shadow-violet-200' : 'text-slate-600 hover:bg-slate-100 hover:text-slate-900' }}">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/>
                </svg>
                {{ $item['label'] }}
                @if($item['route'] === 'siswa.chatbot.index')
                    <span class="ml-auto text-[9px] px-1.5 py-0.5 bg-emerald-500 text-white rounded-full font-semibold">AI</span>
                @endif
            </a>
        @endforeach
    </nav>

    {{-- Logout --}}
    <div class="px-3 py-4 border-t border-slate-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 w-full px-3 py-2.5 rounded-xl text-sm font-medium text-slate-500 hover:bg-red-50 hover:text-red-600 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Main content --}}
<div class="lg:pl-64 flex flex-col min-h-screen">

    {{-- Top bar --}}
    <header class="sticky top-0 z-10 bg-white/80 backdrop-blur-md border-b border-slate-100 px-4 sm:px-6 py-3.5 flex items-center gap-4">
        <button @click="sidebarOpen = !sidebarOpen"
                class="lg:hidden p-2 rounded-xl text-slate-500 hover:bg-slate-100 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <div class="flex-1 min-w-0">
            <h1 class="text-base font-semibold text-slate-800 truncate">{{ $pageTitle }}</h1>
            @if($pageSubtitle)
                <p class="text-xs text-slate-400 truncate">{{ $pageSubtitle }}</p>
            @endif
        </div>

        <a href="{{ route('siswa.questionnaire.index') }}"
           class="hidden sm:flex items-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-600 text-white px-4 py-2 rounded-xl text-sm font-semibold shadow-sm shadow-violet-200 hover:shadow-md hover:shadow-violet-300 transition-all duration-200">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Assessment
        </a>
    </header>

    {{-- Flash messages --}}
    @if(session('success'))
        <div class="mx-4 sm:mx-6 mt-4 bg-emerald-50 border border-emerald-200 text-emerald-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="mx-4 sm:mx-6 mt-4 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-4 h-4 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Page content --}}
    <main class="flex-1 p-4 sm:p-6 page-enter">
        {{ $slot }}
    </main>

    <footer class="py-4 px-6 text-center text-xs text-slate-400 border-t border-slate-100">
        EduCare &copy; {{ date('Y') }} · Sistem Monitoring Risiko Psikososial Remaja
    </footer>
</div>

@stack('scripts')
</body>
</html>
