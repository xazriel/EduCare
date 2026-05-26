<x-siswa-layout>
    <x-slot name="pageTitle">Hasil Assessment</x-slot>
    <x-slot name="pageSubtitle">{{ $response->completed_at?->format('d F Y, H:i') }}</x-slot>

    @php
        $rc = $response->riskClassification;
        $risk = $rc?->overall_risk ?? 'Sedang';
        $riskMap = [
            'Rendah'        => ['gradient'=>'from-emerald-500 to-teal-500',   'bg'=>'bg-emerald-50',  'border'=>'border-emerald-200', 'text'=>'text-emerald-700', 'icon'=>'✅', 'emoji'=>'🌟'],
            'Sedang'        => ['gradient'=>'from-amber-500 to-orange-500',   'bg'=>'bg-amber-50',    'border'=>'border-amber-200',   'text'=>'text-amber-700',   'icon'=>'⚠️', 'emoji'=>'💪'],
            'Tinggi'        => ['gradient'=>'from-red-500 to-rose-600',       'bg'=>'bg-red-50',      'border'=>'border-red-200',     'text'=>'text-red-700',     'icon'=>'🔴', 'emoji'=>'🆘'],
            'Sangat Tinggi' => ['gradient'=>'from-red-700 to-rose-800',       'bg'=>'bg-red-100',     'border'=>'border-red-300',     'text'=>'text-red-800',     'icon'=>'🚨', 'emoji'=>'🚨'],
        ];
        $rc_cfg = $riskMap[$risk] ?? $riskMap['Sedang'];

        $rekomendasi = [
            'Rendah' => [
                ['icon'=>'🎯','title'=>'Pertahankan Pola Baik','desc'=>'Kamu sudah memiliki kebiasaan penggunaan gadget yang sehat. Teruskan!'],
                ['icon'=>'📚','title'=>'Aktif Belajar','desc'=>'Manfaatkan waktu layar untuk hal produktif seperti belajar online.'],
                ['icon'=>'🏃','title'=>'Tetap Aktif Fisik','desc'=>'Olahraga rutin minimal 30 menit sehari untuk jaga kesehatan mental.'],
            ],
            'Sedang' => [
                ['icon'=>'⏱️','title'=>'Batasi Screen Time','desc'=>'Kurangi penggunaan gadget non-produktif menjadi max 2 jam/hari.'],
                ['icon'=>'👥','title'=>'Perbanyak Interaksi Sosial','desc'=>'Luangkan waktu untuk bersosialisasi langsung dengan teman dan keluarga.'],
                ['icon'=>'🤝','title'=>'Konsultasi Guru BK','desc'=>'Bicarakan perasaan dan kekhawatiranmu dengan guru BK untuk panduan lebih lanjut.'],
            ],
            'Tinggi' => [
                ['icon'=>'🏫','title'=>'Segera ke Guru BK','desc'=>'Jadwalkan konseling dengan guru BK sesegera mungkin.'],
                ['icon'=>'📵','title'=>'Digital Detox','desc'=>'Coba satu hari penuh tanpa media sosial dan evaluasi perubahannya.'],
                ['icon'=>'💬','title'=>'Cerita ke Orang Tua','desc'=>'Berbagi dengan orang tua tentang kondisi kamu agar dapat dukungan penuh.'],
            ],
            'Sangat Tinggi' => [
                ['icon'=>'🏥','title'=>'Rujuk Profesional','desc'=>'Segera konsultasikan ke psikolog atau konselor profesional.'],
                ['icon'=>'👨‍👩‍👧','title'=>'Libatkan Keluarga','desc'=>'Keluarga perlu mengetahui kondisi ini untuk memberi dukungan optimal.'],
                ['icon'=>'🚫','title'=>'Hentikan Penggunaan Berlebihan','desc'=>'Batasi penggunaan smartphone dengan bantuan aplikasi kontrol orang tua.'],
            ],
        ];
        $tips = $rekomendasi[$risk] ?? $rekomendasi['Sedang'];
    @endphp

    <div class="max-w-3xl mx-auto space-y-6">

        {{-- Hero Risk Banner --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r {{ $rc_cfg['gradient'] }} p-6 text-white shadow-lg">
            <div class="absolute top-0 right-0 w-48 h-48 rounded-full bg-white/10 -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative flex items-center gap-4">
                <div class="text-5xl">{{ $rc_cfg['emoji'] }}</div>
                <div>
                    <p class="text-white/70 text-sm mb-0.5">Level Risiko Psikososial</p>
                    <h2 class="text-3xl font-extrabold">{{ $risk }}</h2>
                    <p class="text-white/80 text-sm mt-1 max-w-md leading-relaxed">{{ $rc?->recommendation }}</p>
                </div>
            </div>
        </div>

        {{-- Score cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @php
                $scores = [
                    ['label'=>'SDQ',    'score'=>$response->sdq_score,    'max'=>40, 'category'=>$rc?->sdq_category,    'color'=>'violet', 'ranges'=>['Normal (≤15)','Borderline (16–19)','Abnormal (≥20)']],
                    ['label'=>'PSC-17', 'score'=>$response->psc17_score,  'max'=>34, 'category'=>$rc?->psc17_category,  'color'=>'pink',   'ranges'=>['Negatif (<15)','Positif (≥15)']],
                    ['label'=>'SAS-SV', 'score'=>$response->sassv_score,  'max'=>60, 'category'=>$rc?->sassv_category,  'color'=>'amber',  'ranges'=>['Tidak Kecanduan','Kecanduan']],
                ];
                $scoreColors = [
                    'violet'=>['bg'=>'bg-violet-50','border'=>'border-violet-200','text'=>'text-violet-700','bar'=>'bg-violet-500','badge_ok'=>'bg-emerald-100 text-emerald-700','badge_warn'=>'bg-red-100 text-red-700'],
                    'pink'  =>['bg'=>'bg-pink-50',  'border'=>'border-pink-200',  'text'=>'text-pink-700',  'bar'=>'bg-pink-500',  'badge_ok'=>'bg-emerald-100 text-emerald-700','badge_warn'=>'bg-red-100 text-red-700'],
                    'amber' =>['bg'=>'bg-amber-50', 'border'=>'border-amber-200', 'text'=>'text-amber-700', 'bar'=>'bg-amber-500', 'badge_ok'=>'bg-emerald-100 text-emerald-700','badge_warn'=>'bg-red-100 text-red-700'],
                ];
            @endphp
            @foreach($scores as $s)
                @php
                    $sc = $scoreColors[$s['color']];
                    $pct = min(100, round(($s['score'] / $s['max']) * 100));
                    $isWarn = in_array($s['category'], ['Borderline','Abnormal','Positif','Kecanduan']);
                @endphp
                <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
                    <div class="flex items-center justify-between mb-3">
                        <span class="{{ $sc['text'] }} text-xs font-bold uppercase tracking-wide">{{ $s['label'] }}</span>
                        <span class="text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $isWarn ? $sc['badge_warn'] : $sc['badge_ok'] }}">
                            {{ $s['category'] }}
                        </span>
                    </div>
                    <p class="text-3xl font-extrabold text-slate-800 mb-1">{{ $s['score'] }}</p>
                    <p class="text-xs text-slate-400 mb-3">dari {{ $s['max'] }} skor maksimal</p>
                    {{-- Progress bar --}}
                    <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                        <div class="{{ $sc['bar'] }} h-full rounded-full transition-all duration-1000"
                             style="width: {{ $pct }}%"></div>
                    </div>
                    <p class="text-[10px] text-slate-400 mt-2 leading-relaxed">{{ implode(' · ', $s['ranges']) }}</p>
                </div>
            @endforeach
        </div>

        {{-- Chart --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-sm font-semibold text-slate-700 mb-4">Visualisasi Skor</p>
            <div class="h-64">
                <canvas id="resultChart"></canvas>
            </div>
        </div>

        {{-- Rekomendasi --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">💡 Rekomendasi Untukmu</h3>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                @foreach($tips as $tip)
                    <div class="{{ $rc_cfg['bg'] }} {{ $rc_cfg['border'] }} border rounded-xl p-4">
                        <div class="text-2xl mb-2">{{ $tip['icon'] }}</div>
                        <p class="text-sm font-bold text-slate-800 mb-1">{{ $tip['title'] }}</p>
                        <p class="text-xs text-slate-600 leading-relaxed">{{ $tip['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('siswa.chatbot.index') }}"
               class="flex-1 flex items-center justify-center gap-2 bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-bold py-3.5 rounded-xl shadow-md shadow-violet-200 hover:shadow-lg transition text-sm">
                🤖 Tanya EduBot tentang Hasilku
            </a>
            <a href="{{ route('siswa.riwayat.index') }}"
               class="flex-1 flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-700 font-semibold py-3.5 rounded-xl hover:bg-slate-50 transition text-sm">
                📋 Lihat Riwayat
            </a>
            <a href="{{ route('siswa.dashboard') }}"
               class="flex-1 flex items-center justify-center gap-2 bg-white border border-slate-200 text-slate-700 font-semibold py-3.5 rounded-xl hover:bg-slate-50 transition text-sm">
                🏠 Dashboard
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
    new Chart(document.getElementById('resultChart'), {
        type: 'bar',
        data: {
            labels: ['SDQ (max 40)', 'PSC-17 (max 34)', 'SAS-SV (max 60)'],
            datasets: [{
                label: 'Skor Kamu',
                data: [{{ $response->sdq_score }}, {{ $response->psc17_score }}, {{ $response->sassv_score }}],
                backgroundColor: ['rgba(124,58,237,.85)', 'rgba(236,72,153,.85)', 'rgba(245,158,11,.85)'],
                borderRadius: 10,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }, tooltip: { callbacks: { label: ctx => ' Skor: ' + ctx.raw }}},
            scales: {
                x: { grid: { display: false }, ticks: { font: { size: 11, family:'Inter' }}},
                y: { grid: { color: '#f1f5f9' }, beginAtZero: true, ticks: { font: { size: 11 }}}
            }
        }
    });
    </script>
    @endpush
</x-siswa-layout>
