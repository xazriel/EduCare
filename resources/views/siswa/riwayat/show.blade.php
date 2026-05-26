<x-siswa-layout>
    <x-slot name="pageTitle">Detail Riwayat</x-slot>
    <x-slot name="pageSubtitle">{{ $response->completed_at?->format('d F Y, H:i') }} WIB</x-slot>

    @php
        $rc = $response->riskClassification;
        $risk = $rc?->overall_risk ?? '-';
        $riskMap = [
            'Rendah'        => ['gradient'=>'from-emerald-500 to-teal-500',  'bg'=>'bg-emerald-50', 'border'=>'border-emerald-200', 'text'=>'text-emerald-700', 'emoji'=>'✅'],
            'Sedang'        => ['gradient'=>'from-amber-500 to-orange-500',  'bg'=>'bg-amber-50',   'border'=>'border-amber-200',   'text'=>'text-amber-700',   'emoji'=>'⚠️'],
            'Tinggi'        => ['gradient'=>'from-red-500 to-rose-600',      'bg'=>'bg-red-50',     'border'=>'border-red-200',     'text'=>'text-red-700',     'emoji'=>'🔴'],
            'Sangat Tinggi' => ['gradient'=>'from-red-700 to-rose-800',      'bg'=>'bg-red-100',    'border'=>'border-red-300',     'text'=>'text-red-800',     'emoji'=>'🚨'],
        ];
        $cfg = $riskMap[$risk] ?? $riskMap['Sedang'];
    @endphp

    <div class="max-w-2xl mx-auto space-y-5">

        {{-- Back --}}
        <a href="{{ route('siswa.riwayat.index') }}"
           class="inline-flex items-center gap-2 text-sm text-slate-500 hover:text-violet-600 font-medium transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
            Kembali ke Riwayat
        </a>

        {{-- Risk Banner --}}
        <div class="relative overflow-hidden rounded-2xl bg-gradient-to-r {{ $cfg['gradient'] }} p-6 text-white shadow-lg">
            <div class="absolute top-0 right-0 w-48 h-48 rounded-full bg-white/10 -translate-y-1/2 translate-x-1/2"></div>
            <div class="relative flex items-center gap-4">
                <div class="text-4xl">{{ $cfg['emoji'] }}</div>
                <div>
                    <p class="text-white/70 text-xs mb-0.5">Level Risiko Psikososial</p>
                    <h2 class="text-2xl font-extrabold">{{ $risk }}</h2>
                    <p class="text-white/80 text-xs mt-1 leading-relaxed max-w-sm">{{ $rc?->recommendation }}</p>
                </div>
            </div>
        </div>

        {{-- Score cards --}}
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                ['SDQ','sdq_score','sdq_category','violet'],
                ['PSC-17','psc17_score','psc17_category','pink'],
                ['SAS-SV','sassv_score','sassv_category','amber'],
            ] as [$lbl,$sKey,$cKey,$color])
                @php
                    $colorMap = ['violet'=>'bg-violet-50 border-violet-200 text-violet-700','pink'=>'bg-pink-50 border-pink-200 text-pink-700','amber'=>'bg-amber-50 border-amber-200 text-amber-700'];
                    $isWarn = in_array($rc?->$cKey, ['Borderline','Abnormal','Positif','Kecanduan']);
                @endphp
                <div class="{{ $colorMap[$color] }} border rounded-2xl p-4 text-center">
                    <p class="text-xs font-bold uppercase tracking-wide mb-1 opacity-70">{{ $lbl }}</p>
                    <p class="text-3xl font-extrabold text-slate-800">{{ $response->$sKey }}</p>
                    <span class="inline-block mt-1.5 text-[10px] px-2 py-0.5 rounded-full font-semibold {{ $isWarn ? 'bg-red-100 text-red-700' : 'bg-emerald-100 text-emerald-700' }}">
                        {{ $rc?->$cKey ?? '-' }}
                    </span>
                </div>
            @endforeach
        </div>

        {{-- Chart --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <p class="text-sm font-semibold text-slate-700 mb-4">Visualisasi Skor</p>
            <div class="h-48">
                <canvas id="detailChart"></canvas>
            </div>
        </div>

        {{-- Detail subskala --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <h3 class="text-sm font-semibold text-slate-700 mb-4">Detail Klasifikasi</h3>
            <div class="space-y-3">
                @foreach([
                    ['SDQ', $rc?->sdq_category,   'Normal → kondisi baik', 'Borderline/Abnormal → perlu perhatian'],
                    ['PSC-17', $rc?->psc17_category, 'Negatif → tidak terdeteksi', 'Positif → perlu evaluasi lebih'],
                    ['SAS-SV', $rc?->sassv_category, 'Tidak Kecanduan → penggunaan wajar', 'Kecanduan → perlu intervensi'],
                ] as [$name, $cat, $ok, $warn])
                    @php
                        $isWarn2 = in_array($cat, ['Borderline','Abnormal','Positif','Kecanduan']);
                    @endphp
                    <div class="flex items-start gap-3 p-3 rounded-xl {{ $isWarn2 ? 'bg-red-50' : 'bg-emerald-50' }}">
                        <div class="flex-shrink-0 w-7 h-7 rounded-lg {{ $isWarn2 ? 'bg-red-500' : 'bg-emerald-500' }} flex items-center justify-center text-white text-xs font-bold">
                            {{ $isWarn2 ? '!' : '✓' }}
                        </div>
                        <div>
                            <p class="text-xs font-bold text-slate-700">{{ $name }}</p>
                            <p class="text-xs font-semibold {{ $isWarn2 ? 'text-red-700' : 'text-emerald-700' }}">{{ $cat }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $isWarn2 ? $warn : $ok }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Actions --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('siswa.questionnaire.index') }}"
               class="flex-1 text-center bg-gradient-to-r from-violet-600 to-indigo-600 text-white font-bold py-3 rounded-xl text-sm shadow-md shadow-violet-200 hover:shadow-lg transition">
                🔄 Assessment Ulang
            </a>
            <a href="{{ route('siswa.chatbot.index') }}"
               class="flex-1 text-center bg-white border border-slate-200 text-slate-700 font-semibold py-3 rounded-xl text-sm hover:bg-slate-50 transition">
                🤖 Tanya EduBot
            </a>
        </div>
    </div>

    @push('scripts')
    <script>
    new Chart(document.getElementById('detailChart'), {
        type: 'radar',
        data: {
            labels: ['SDQ', 'PSC-17', 'SAS-SV'],
            datasets: [{
                label: 'Skor',
                data: [
                    Math.round(({{ $response->sdq_score }} / 40) * 100),
                    Math.round(({{ $response->psc17_score }} / 34) * 100),
                    Math.round(({{ $response->sassv_score }} / 60) * 100),
                ],
                borderColor: '#7c3aed',
                backgroundColor: 'rgba(124,58,237,0.15)',
                pointBackgroundColor: '#7c3aed',
                pointRadius: 5,
                borderWidth: 2,
            }]
        },
        options: {
            responsive: true, maintainAspectRatio: false,
            plugins: { legend: { display: false }},
            scales: {
                r: {
                    beginAtZero: true, max: 100,
                    ticks: { stepSize: 25, font: { size:10 }, backdropColor: 'transparent' },
                    pointLabels: { font: { size:12, weight:'600' }},
                    grid: { color: '#e2e8f0' },
                    angleLines: { color: '#e2e8f0' }
                }
            }
        }
    });
    </script>
    @endpush
</x-siswa-layout>
