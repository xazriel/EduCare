<x-guru-bk-layout pageTitle="Dashboard Pemantauan Guru BK" pageSubtitle="Sekilas kondisi psikososial dan tingkat ketergantungan gawai seluruh siswa.">

    <div class="space-y-6">
        {{-- 1. Kartu Ringkasan Statistik --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-5">
            {{-- Total Siswa --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Total Siswa</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $totalStudents }}</p>
                </div>
            </div>

            {{-- Partisipasi --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Siswa Partisipan</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $completedStudents }} <span class="text-xs text-slate-500 font-normal">({{ $participationRate }}%)</span></p>
                </div>
            </div>

            {{-- Belum Assessment --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-slate-500">Belum Assessment</p>
                    <p class="text-2xl font-bold text-slate-800">{{ $nonCompletedStudents }}</p>
                </div>
            </div>

            {{-- Siswa Risiko Tinggi --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex items-center gap-4 bg-gradient-to-br from-white to-rose-50/20">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-xs font-semibold text-rose-700">Berisiko Psikososial</p>
                    <p class="text-2xl font-bold text-rose-800">{{ $highRiskStudentsCount }} <span class="text-xs text-rose-500 font-normal">siswa</span></p>
                </div>
            </div>
        </div>

        {{-- 2. Grafik Utama --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Donut Chart Risiko Psikososial --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm lg:col-span-1 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Distribusi Kategori Risiko Sekolah</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Berdasarkan kompilasi kuesioner siswa terakhir</p>
                </div>
                <div class="my-6 flex justify-center items-center relative" style="height: 220px;">
                    <canvas id="riskDoughnutChart"></canvas>
                </div>
                <div class="grid grid-cols-2 gap-3 text-xs border-t border-slate-50 pt-4">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                        <span class="text-slate-600">Tidak Berisiko: <strong>{{ $riskCounts['Tidak Berisiko Psikososial'] }}</strong></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-full bg-rose-600"></span>
                        <span class="text-slate-600">Berisiko: <strong>{{ $riskCounts['Berisiko Psikososial'] }}</strong></span>
                    </div>
                </div>
            </div>

            {{-- Line Chart Ketergantungan Gawai --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm lg:col-span-2 flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Tren Tingkat Kecanduan Gawai Bulanan</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Skor rata-rata instrumen SAS-SV sekolah 6 bulan terakhir</p>
                </div>
                <div class="my-4" style="height: 250px;">
                    @if(count($monthlyScores) > 0)
                        <canvas id="gadgetTrendLineChart"></canvas>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <p class="text-xs">Data kuesioner bulanan belum tersedia</p>
                        </div>
                    @endif
                </div>
                <div class="text-[11px] text-slate-500 font-medium flex items-center gap-1">
                    <svg class="w-3.5 h-3.5 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span>Skor SAS-SV maksimal adalah 50. Skor > 31 untuk laki-laki dan > 32 untuk perempuan tergolong risiko kecanduan.</span>
                </div>
            </div>
        </div>

        {{-- 3. Distribusi Status Risiko Siswa Per Kelas --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Distribusi Status Risiko per Kelas</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Rangkuman skor instrumen rata-rata dan status per kelas</p>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-3.5">Nama Kelas</th>
                            <th class="px-5 py-3.5">Total Siswa</th>
                            <th class="px-5 py-3.5">Partisipasi</th>
                            <th class="px-5 py-3.5 text-center">Rata-rata SDQ</th>
                            <th class="px-5 py-3.5 text-center">Rata-rata PSC-17</th>
                            <th class="px-5 py-3.5 text-center">Rata-rata SAS-SV</th>
                            <th class="px-5 py-3.5">Peta Distribusi Risiko</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @foreach($classesData as $class)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4 font-bold text-slate-800">{{ $class['name'] }}</td>
                                <td class="px-5 py-4">{{ $class['total_students'] }} siswa</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-16 bg-slate-100 rounded-full h-1.5 overflow-hidden">
                                            <div class="bg-indigo-500 h-full rounded-full" style="width: {{ $class['total_students'] > 0 ? ($class['completed_count'] / $class['total_students']) * 100 : 0 }}%"></div>
                                        </div>
                                        <span>{{ $class['completed_count'] }}/{{ $class['total_students'] }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4 text-center font-semibold text-violet-600">{{ $class['avg_sdq'] }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-emerald-600">{{ $class['avg_psc'] }}</td>
                                <td class="px-5 py-4 text-center font-semibold text-sky-600">{{ $class['avg_sassv'] }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-1.5">
                                        @if($class['risks']['Tidak Berisiko Psikososial'] > 0)
                                            <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 text-[10px] font-medium border border-emerald-100">
                                                Tidak Berisiko: {{ $class['risks']['Tidak Berisiko Psikososial'] }}
                                            </span>
                                        @endif
                                        @if($class['risks']['Berisiko Psikososial'] > 0)
                                            <span class="px-2 py-0.5 rounded bg-rose-50 text-rose-700 text-[10px] font-medium border border-rose-100 animate-pulse">
                                                Berisiko: {{ $class['risks']['Berisiko Psikososial'] }}
                                            </span>
                                        @endif
                                        @if($class['completed_count'] === 0)
                                            <span class="text-slate-500 font-medium italic text-[11px]">Belum ada penilaian</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- 4. Riwayat Assessment Terbaru --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Hasil Kuesioner Terbaru Siswa</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Daftar 5 aktivitas pengerjaan kuesioner terakhir</p>
                </div>
                <a href="{{ route('guruBk.siswa.index') }}" class="text-xs text-indigo-600 font-semibold hover:text-indigo-800 transition">
                    Lihat Semua Siswa &rarr;
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-3.5">Nama Siswa</th>
                            <th class="px-5 py-3.5">Kelas</th>
                            <th class="px-5 py-3.5">Tanggal Selesai</th>
                            <th class="px-5 py-3.5 text-center">Skor SDQ</th>
                            <th class="px-5 py-3.5 text-center">Skor PSC-17</th>
                            <th class="px-5 py-3.5 text-center">Skor SAS-SV</th>
                            <th class="px-5 py-3.5">Risiko Keseluruhan</th>
                            <th class="px-5 py-3.5 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($recentAssessments as $resp)
                            @php
                                $overall = $resp->riskClassification->overall_risk ?? 'Tidak Berisiko Psikososial';
                                $badgeColor = match($overall) {
                                    'Tidak Berisiko Psikososial' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    default => 'bg-rose-50 text-rose-700 border-rose-100',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-2">
                                        <div class="w-7 h-7 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center font-bold text-[10px]">
                                            {{ strtoupper(substr($resp->user->name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-slate-800">{{ $resp->user->name }}</span>
                                    </div>
                                </td>
                                <td class="px-5 py-4">{{ $resp->user->studentClass->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-slate-500">{{ $resp->completed_at->format('d M Y, H:i') }}</td>
                                <td class="px-5 py-4 text-center font-semibold">{{ $resp->sdq_score }}</td>
                                <td class="px-5 py-4 text-center font-semibold">{{ $resp->psc17_score }}</td>
                                <td class="px-5 py-4 text-center font-semibold">{{ $resp->sassv_score }}</td>
                                <td class="px-5 py-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeColor }}">
                                        {{ $overall }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('guruBk.siswa.show', $resp->user_id) }}" class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-semibold transition text-[11px]">
                                        Profil & Rencana Preventif
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-8 text-center text-slate-500 font-medium italic">Belum ada assessment siswa yang diselesaikan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. DOUGHNUT CHART - DISTRIBUSI KATEGORI RISIKO
            const ctxRisk = document.getElementById('riskDoughnutChart').getContext('2d');
            new Chart(ctxRisk, {
                type: 'doughnut',
                data: {
                    labels: ['Tidak Berisiko Psikososial', 'Berisiko Psikososial'],
                    datasets: [{
                        data: [
                            {{ $riskCounts['Tidak Berisiko Psikososial'] }},
                            {{ $riskCounts['Berisiko Psikososial'] }}
                        ],
                        backgroundColor: ['#10b981', '#e11d48'],
                        borderWidth: 2,
                        borderColor: '#ffffff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return ` ${context.label}: ${context.raw} siswa`;
                                }
                            }
                        }
                    },
                    cutout: '70%'
                }
            });

            // 2. LINE CHART - TREN BULANAN KECANDUAN GAWAI
            @if(count($monthlyScores) > 0)
            const ctxGadget = document.getElementById('gadgetTrendLineChart').getContext('2d');
            new Chart(ctxGadget, {
                type: 'line',
                data: {
                    labels: {!! json_encode($monthlyLabels) !!},
                    datasets: [{
                        label: 'Skor Rata-rata SAS-SV',
                        data: {!! json_encode($monthlyScores) !!},
                        borderColor: '#0284c7',
                        backgroundColor: 'rgba(2, 132, 199, 0.05)',
                        borderWidth: 3,
                        pointBackgroundColor: '#0284c7',
                        pointHoverRadius: 6,
                        tension: 0.3,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        y: {
                            min: 10,
                            max: 50,
                            ticks: {
                                stepSize: 10
                            },
                            grid: {
                                color: '#f1f5f9'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
            @endif
        });
    </script>
    @endpush
</x-guru-bk-layout>