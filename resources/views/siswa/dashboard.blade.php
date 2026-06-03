<x-siswa-layout>
    <x-slot name="pageTitle">Dashboard</x-slot>
    <x-slot name="pageSubtitle">Selamat datang kembali, {{ Auth::user()->name }}</x-slot>

    {{-- Welcome Banner --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r from-violet-600 via-indigo-600 to-sky-600 p-6 mb-6 shadow-lg shadow-violet-200">
        <div class="absolute top-0 right-0 w-64 h-64 rounded-full bg-white/5 -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-20 w-32 h-32 rounded-full bg-white/5 translate-y-1/2"></div>
        <div class="relative">
            <p class="text-violet-200 text-sm mb-1">Hai, selamat datang 👋</p>
            <h2 class="text-white text-2xl font-bold mb-0.5">{{ Auth::user()->name }}</h2>
            <p class="text-violet-200 text-sm">
                {{ Auth::user()->studentClass->name ?? 'Siswa EduCare' }}
                @if(Auth::user()->nis) · NIS {{ Auth::user()->nis }} @endif
            </p>
        </div>
        <div class="relative mt-4 flex items-center gap-3">
            @if($lastResponse)
                <span class="inline-flex items-center gap-1.5 bg-white/15 backdrop-blur-sm text-white text-xs font-medium px-3 py-1.5 rounded-full border border-white/20">
                    <span class="w-1.5 h-1.5 rounded-full
                        @if($lastResponse->riskClassification?->overall_risk === 'Tidak Berisiko Psikososial') bg-emerald-400
                        @else bg-red-400 @endif"></span>
                    {{ $lastResponse->riskClassification?->overall_risk ?? '-' }}
                </span>
                <span class="text-white/60 text-xs">Assessment terakhir: {{ $lastResponse->completed_at?->diffForHumans() }}</span>
            @else
                <span class="inline-flex items-center gap-1.5 bg-white/15 text-white text-xs font-medium px-3 py-1.5 rounded-full border border-white/20">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span>
                    Belum ada assessment
                </span>
            @endif
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        {{-- Total Assessment --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-violet-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $totalCompleted }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Total Assessment</p>
        </div>

        {{-- Skor SDQ --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-blue-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $lastResponse?->sdq_score ?? '–' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Skor SDQ</p>
        </div>

        {{-- Skor PSC-17 --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-pink-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $lastResponse?->psc17_score ?? '–' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Skor PSC-17</p>
        </div>

        {{-- Skor SAS-SV --}}
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <div class="w-9 h-9 rounded-xl bg-amber-100 flex items-center justify-center">
                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-2xl font-bold text-slate-800">{{ $lastResponse?->sassv_score ?? '–' }}</p>
            <p class="text-xs text-slate-500 mt-0.5">Skor SAS-SV</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Level Risiko + CTA --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Risk Level Card --}}
            @if($lastResponse?->riskClassification)
                @php
                    $risk = $lastResponse->riskClassification->overall_risk;
                    $riskConfig = [
                        'Tidak Berisiko Psikososial' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-200', 'text' => 'text-emerald-700', 'badge' => 'bg-emerald-500', 'icon' => '✅'],
                        'Berisiko Psikososial'       => ['bg' => 'bg-red-50',     'border' => 'border-red-200',     'text' => 'text-red-700',     'badge' => 'bg-red-500',     'icon' => '🚨'],
                    ];
                    $cfg = $riskConfig[$risk] ?? $riskConfig['Berisiko Psikososial'];
                @endphp
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Status Risiko Terakhir</p>
                    <div class="{{ $cfg['bg'] }} {{ $cfg['border'] }} border rounded-xl p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xl">{{ $cfg['icon'] }}</span>
                            <span class="{{ $cfg['text'] }} font-bold text-sm sm:text-base leading-snug">{{ $risk }}</span>
                        </div>
                        <p class="{{ $cfg['text'] }} text-xs leading-relaxed opacity-85">
                            {{ $lastResponse->riskClassification->recommendation }}
                        </p>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Level Risiko</p>
                    <div class="bg-slate-50 border border-slate-200 rounded-xl p-4 text-center">
                        <p class="text-3xl mb-2">📊</p>
                        <p class="text-sm font-semibold text-slate-600">Belum Ada Data</p>
                        <p class="text-xs text-slate-400 mt-1">Selesaikan assessment untuk melihat level risiko</p>
                    </div>
                </div>
            @endif

            {{-- CTA Assessment --}}
            <div class="bg-gradient-to-br from-violet-600 to-indigo-700 rounded-2xl p-5 text-white shadow-lg shadow-violet-200">
                <p class="font-bold text-sm mb-1">
                    {{ $totalCompleted > 0 ? 'Assessment Baru' : 'Mulai Assessment' }}
                </p>
                <p class="text-violet-200 text-xs mb-4 leading-relaxed">
                    {{ $totalCompleted > 0
                        ? 'Lakukan assessment ulang untuk memantau perkembanganmu.'
                        : 'Jawab 52 pertanyaan untuk mengetahui kondisi psikososialmu.' }}
                </p>
                <a href="{{ route('siswa.questionnaire.index') }}"
                   class="block bg-white text-violet-700 text-center text-sm font-bold py-2.5 rounded-xl hover:bg-violet-50 transition-colors duration-200">
                    {{ $totalCompleted > 0 ? 'Isi Assessment Lagi →' : 'Mulai Sekarang →' }}
                </a>
            </div>

            {{-- Quick Links --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <p class="text-xs font-semibold text-slate-400 uppercase tracking-wide mb-3">Menu Cepat</p>
                <div class="space-y-2">
                    <a href="{{ route('siswa.riwayat.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium group">
                        <span class="w-7 h-7 rounded-lg bg-violet-100 flex items-center justify-center group-hover:bg-violet-200 transition">📋</span>
                        Lihat Riwayat
                    </a>
                    <a href="{{ route('siswa.chatbot.index') }}"
                       class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition text-sm text-slate-700 font-medium group">
                        <span class="w-7 h-7 rounded-lg bg-emerald-100 flex items-center justify-center group-hover:bg-emerald-200 transition">🤖</span>
                        Tanya EduBot
                    </a>
                </div>
            </div>
        </div>

        {{-- Chart Perkembangan --}}
        <div class="lg:col-span-2 space-y-4">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-semibold text-slate-700">Perkembangan Skor</p>
                    <a href="{{ route('siswa.riwayat.index') }}" class="text-xs text-violet-600 hover:underline font-medium">Lihat semua</a>
                </div>
                @if($history->count() > 0)
                    <div class="h-48">
                        <canvas id="progressChart"></canvas>
                    </div>
                @else
                    <div class="h-48 flex flex-col items-center justify-center text-center">
                        <p class="text-3xl mb-2">📈</p>
                        <p class="text-sm font-semibold text-slate-500">Belum ada data grafik</p>
                        <p class="text-xs text-slate-400 mt-1">Selesaikan assessment pertama untuk melihat perkembangan</p>
                    </div>
                @endif
            </div>

            {{-- Riwayat Singkat --}}
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-4">
                    <p class="text-sm font-semibold text-slate-700">Riwayat Terakhir</p>
                    <a href="{{ route('siswa.riwayat.index') }}" class="text-xs text-violet-600 hover:underline font-medium">Semua →</a>
                </div>
                @if($history->count() > 0)
                    <div class="space-y-2">
                        @foreach($history->reverse() as $item)
                            @php
                                $r = $item->riskClassification?->overall_risk ?? '-';
                                $badge = $r === 'Tidak Berisiko Psikososial' ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-700';
                            @endphp
                            <a href="{{ route('siswa.riwayat.show', $item->id) }}"
                               class="flex items-center gap-3 px-3 py-2.5 rounded-xl hover:bg-slate-50 transition group">
                                <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center text-sm font-bold text-violet-600 flex-shrink-0">
                                    {{ $loop->iteration }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-semibold text-slate-700 truncate">
                                        {{ $item->completed_at?->format('d M Y') }}
                                    </p>
                                    <p class="text-[10px] text-slate-400">
                                        SDQ: {{ $item->sdq_score }} · PSC: {{ $item->psc17_score }} · SAS: {{ $item->sassv_score }}
                                    </p>
                                </div>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold {{ $badge }} flex-shrink-0">{{ $r }}</span>
                            </a>
                        @endforeach
                    </div>
                @else
                    <p class="text-sm text-slate-400 text-center py-4">Belum ada riwayat assessment.</p>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    @if($history->count() > 0)
    <script>
    const labels = @json($history->map(fn($h) => $h->completed_at?->format('d M')));
    const sdq    = @json($history->map(fn($h) => $h->sdq_score));
    const psc    = @json($history->map(fn($h) => $h->psc17_score));
    const sas    = @json($history->map(fn($h) => $h->sassv_score));

    new Chart(document.getElementById('progressChart'), {
        type: 'line',
        data: {
            labels,
            datasets: [
                { label: 'SDQ',   data: sdq, borderColor: '#7c3aed', backgroundColor: '#7c3aed20', tension: 0.4, fill: true, pointBackgroundColor: '#7c3aed', pointRadius: 4 },
                { label: 'PSC-17',data: psc, borderColor: '#ec4899', backgroundColor: '#ec489910', tension: 0.4, fill: false, pointBackgroundColor: '#ec4899', pointRadius: 4 },
                { label: 'SAS-SV',data: sas, borderColor: '#f59e0b', backgroundColor: '#f59e0b10', tension: 0.4, fill: false, pointBackgroundColor: '#f59e0b', pointRadius: 4 },
            ]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { labels: { font: { size: 11, family: 'Inter' }, padding: 12, boxWidth: 10, usePointStyle: true }}},
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 10 }}},
                y: { grid: { color: '#f1f5f9' }, ticks: { font: { size: 10 }}, beginAtZero: true }
            }
        }
    });
    </script>
    @endif
    @endpush
</x-siswa-layout>