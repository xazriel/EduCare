<x-guru-bk-layout pageTitle="Dashboard Analisis Business Intelligence (BI)" pageSubtitle="Analisis data makro untuk mengkorelasikan penggunaan gawai dengan indikasi gangguan perilaku.">

    <div class="space-y-6">
        {{-- Row 1: Demografi Gender & Perbandingan Kelas --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Sebaran Risiko per Gender --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Sebaran Tingkat Risiko per Gender</h3>
                    <p class="text-xs text-slate-400 mt-1">Perbandingan klasifikasi tingkat risiko antara siswa laki-laki dan perempuan</p>
                </div>
                <div class="my-4" style="height: 220px;">
                    <canvas id="genderRiskChart"></canvas>
                </div>
                <div class="text-[11px] text-slate-400">
                    *Membantu menganalisis apakah kerentanan psikososial dipengaruhi oleh aspek gender siswa.
                </div>
            </div>

            {{-- Perbandingan Skor Awal per Kelas --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Skor Rata-rata Instrumen per Kelas</h3>
                    <p class="text-xs text-slate-400 mt-1">Perbandingan tingkat keparahan perilaku, psikososial, dan ketergantungan gawai</p>
                </div>
                <div class="my-4" style="height: 220px;">
                    <canvas id="classScoresChart"></canvas>
                </div>
                <div class="text-[11px] text-slate-400">
                    *Perbandingan skor antar tingkatan kelas untuk mendeteksi klaster/kelompok risiko tinggi.
                </div>
            </div>
        </div>

        {{-- Row 2: Tren Bulanan Sekolah --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
            <div>
                <h3 class="font-bold text-slate-800 text-sm">Tren Perkembangan Psikososial Bulanan Sekolah</h3>
                <p class="text-xs text-slate-400 mt-1">Fluktuasi rata-rata skor SDQ, PSC-17, dan SAS-SV sekolah secara berkala</p>
            </div>
            <div class="my-4" style="height: 250px;">
                @if(count($trendLabels) > 0)
                    <canvas id="schoolTrendChart"></canvas>
                @else
                    <div class="h-full flex flex-col items-center justify-center text-slate-400">
                        <p class="text-xs">Data kuesioner bulanan belum terkumpul</p>
                    </div>
                @endif
            </div>
            <div class="text-[11px] text-slate-400">
                *Grafik multi-tren untuk mengamati apakah peningkatan kecanduan gawai sejalan dengan peningkatan skor kesulitan perilaku sekolah.
            </div>
        </div>

        {{-- Row 3: Analisis Korelasi Scatter Plot --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <div class="mb-4">
                <h3 class="font-bold text-slate-800 text-sm">Analisis Korelasi: Ketergantungan Gawai vs Risiko Perilaku</h3>
                <p class="text-xs text-slate-400 mt-1">Scatter plot hubungan antara skor kecanduan gawai (SAS-SV) dengan skor kesulitan perilaku (SDQ) individu siswa</p>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 items-center">
                <div class="lg:col-span-3" style="height: 350px;">
                    @if(count($correlationPoints) > 0)
                        <canvas id="correlationScatterChart"></canvas>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-slate-400 border border-dashed border-slate-200 rounded-xl">
                            <p class="text-xs">Data korelasi belum cukup untuk dianalisis</p>
                        </div>
                    @endif
                </div>
                <div class="lg:col-span-1 space-y-4 bg-slate-50 rounded-2xl p-5 border border-slate-100 text-xs">
                    <h4 class="font-bold text-slate-800">Interpretasi Analisis Korelasi</h4>
                    <p class="text-slate-600 leading-relaxed">
                        Setiap titik mewakili satu sesi kuesioner yang diselesaikan oleh siswa.
                    </p>
                    <div class="space-y-2">
                        <div class="p-2.5 rounded-lg bg-emerald-50 text-emerald-800 border border-emerald-100">
                            <strong>Kuadran Kiri Bawah:</strong><br>
                            Penggunaan gawai aman & kesehatan emosional/perilaku normal (Risiko Rendah).
                        </div>
                        <div class="p-2.5 rounded-lg bg-rose-50 text-rose-800 border border-rose-100">
                            <strong>Kuadran Kanan Atas:</strong><br>
                            Kecanduan gawai berat sejalan dengan tingkat kesulitan perilaku yang abnormal (Risiko Tinggi).
                        </div>
                    </div>
                    <p class="text-[10px] text-slate-400 leading-normal">
                        Hubungan linear positif (tren titik naik dari kiri ke kanan) mengonfirmasi bahwa ketergantungan gawai memicu peningkatan gangguan perilaku anak secara klinis.
                    </p>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // 1. STACKED BAR CHART - RISIKO PER GENDER
            const ctxGender = document.getElementById('genderRiskChart').getContext('2d');
            new Chart(ctxGender, {
                type: 'bar',
                data: {
                    labels: ['Siswa Laki-laki (L)', 'Siswa Perempuan (P)'],
                    datasets: [
                        {
                            label: 'Tidak Berisiko Psikososial',
                            data: [{{ $genderRisks['L']['Tidak Berisiko Psikososial'] }}, {{ $genderRisks['P']['Tidak Berisiko Psikososial'] }}],
                            backgroundColor: '#10b981'
                        },
                        {
                            label: 'Berisiko Psikososial',
                            data: [{{ $genderRisks['L']['Berisiko Psikososial'] }}, {{ $genderRisks['P']['Berisiko Psikososial'] }}],
                            backgroundColor: '#e11d48'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: {
                            stacked: true,
                            grid: { display: false }
                        },
                        y: {
                            stacked: true,
                            grid: { color: '#f1f5f9' },
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                    }
                }
            });

            // 2. BAR CHART - RATA-RATA SKOR PER KELAS
            const ctxClass = document.getElementById('classScoresChart').getContext('2d');
            new Chart(ctxClass, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($classLabels) !!},
                    datasets: [
                        {
                            label: 'Rata SDQ (Kesulitan)',
                            data: {!! json_encode($classSdq) !!},
                            backgroundColor: 'rgba(124, 58, 237, 0.75)',
                            borderColor: '#7c3aed',
                            borderWidth: 1.5
                        },
                        {
                            label: 'Rata PSC-17 (Psikososial)',
                            data: {!! json_encode($classPsc) !!},
                            backgroundColor: 'rgba(16, 185, 129, 0.75)',
                            borderColor: '#10b981',
                            borderWidth: 1.5
                        },
                        {
                            label: 'Rata SAS-SV (Gadget)',
                            data: {!! json_encode($classSassv) !!},
                            backgroundColor: 'rgba(14, 165, 233, 0.75)',
                            borderColor: '#0ea5e9',
                            borderWidth: 1.5
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { grid: { display: false } },
                        y: {
                            grid: { color: '#f1f5f9' },
                            max: 50
                        }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } }
                    }
                }
            });

            // 3. MULTI-LINE CHART - TREN SEKOLAH
            @if(count($trendLabels) > 0)
            const ctxSchool = document.getElementById('schoolTrendChart').getContext('2d');
            new Chart(ctxSchool, {
                type: 'line',
                data: {
                    labels: {!! json_encode($trendLabels) !!},
                    datasets: [
                        {
                            label: 'Rata-rata SDQ (Kesulitan)',
                            data: {!! json_encode($trendSdq) !!},
                            borderColor: '#7c3aed',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#7c3aed',
                            tension: 0.25
                        },
                        {
                            label: 'Rata-rata PSC-17 (Psikososial)',
                            data: {!! json_encode($trendPsc) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            tension: 0.25
                        },
                        {
                            label: 'Rata-rata SAS-SV (Gawai)',
                            data: {!! json_encode($trendSassv) !!},
                            borderColor: '#0ea5e9',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#0ea5e9',
                            tension: 0.25
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 5,
                            max: 45,
                            grid: { color: '#f1f5f9' }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } }
                    }
                }
            });
            @endif

            // 4. SCATTER PLOT - ANALISIS KORELASI SAS-SV VS SDQ
            @if(count($correlationPoints) > 0)
            const ctxScatter = document.getElementById('correlationScatterChart').getContext('2d');
            new Chart(ctxScatter, {
                type: 'scatter',
                data: {
                    datasets: [{
                        label: 'Siswa Terdaftar',
                        data: {!! json_encode($correlationPoints) !!}.map(p => ({ x: p.x, y: p.y_sdq, label: p.name })),
                        backgroundColor: 'rgba(225, 29, 72, 0.65)',
                        borderColor: '#e11d48',
                        pointRadius: 6,
                        pointHoverRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const p = context.raw;
                                    return ` ${p.label} (SAS-SV: ${p.x}, SDQ: ${p.y})`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Tingkat Kecanduan Gawai (Skor SAS-SV) ➔',
                                font: { weight: 'bold', size: 11 }
                            },
                            min: 10,
                            max: 50,
                            grid: { color: '#f8fafc' }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Tingkat Kesulitan Perilaku & Emosional (Skor SDQ) ➔',
                                font: { weight: 'bold', size: 11 }
                            },
                            min: 0,
                            max: 40,
                            grid: { color: '#f1f5f9' }
                        }
                    }
                }
            });
            @endif
        });
    </script>
    @endpush
</x-guru-bk-layout>
