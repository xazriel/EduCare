<x-guru-bk-layout pageTitle="Profil & Rencana Preventif Siswa" pageSubtitle="Visualisasi rekam jejak psikososial individu dan pengelolaan intervensi dini.">

    <div class="space-y-6" x-data="{ activeTab: 'trend' }">
        {{-- Tombol Kembali --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('guruBk.siswa.index') }}" class="inline-flex items-center gap-1.5 text-xs text-slate-500 hover:text-slate-800 font-semibold transition">
                &larr; Kembali ke Daftar Siswa
            </a>
            <span class="text-xs text-slate-500 font-semibold">NIS: <strong>{{ $student->nis ?? '-' }}</strong></span>
        </div>

        {{-- 1. Informasi Ringkas Siswa --}}
        <div class="bg-gradient-to-br from-indigo-900 to-indigo-800 rounded-3xl p-6 text-white shadow-xl relative overflow-hidden">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/5 rounded-full blur-2xl"></div>
            <div class="absolute -left-10 -top-10 w-32 h-32 bg-white/5 rounded-full blur-2xl"></div>

            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative z-10">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/10 text-white flex items-center justify-center font-bold text-xl border border-white/10 shadow-inner flex-shrink-0">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div>
                        <h2 class="text-xl font-bold">{{ $student->name }}</h2>
                        <p class="text-xs text-indigo-200 mt-1">
                            Kelas {{ $student->studentClass->name ?? 'Belum Diatur' }} &middot; 
                            {{ ($student->gender === 'L' || $student->gender === 'l') ? 'Laki-laki' : 'Perempuan' }} &middot; 
                            {{ $student->email }}
                        </p>
                    </div>
                </div>

                {{-- Status Klasifikasi Terakhir --}}
                <div class="flex items-center gap-3">
                    <div class="text-left md:text-right">
                        <p class="text-[10px] text-indigo-300 font-semibold tracking-wider uppercase">Tingkat Risiko Terakhir</p>
                        @if($latestResponse && $latestResponse->riskClassification)
                            <h3 class="text-lg font-extrabold mt-0.5">{{ $latestResponse->riskClassification->overall_risk }}</h3>
                        @else
                            <h3 class="text-lg font-extrabold text-indigo-300 mt-0.5">Belum Dinilai</h3>
                        @endif
                    </div>
                    @if($latestResponse && $latestResponse->riskClassification)
                        @php
                            $riskBg = match($latestResponse->riskClassification->overall_risk) {
                                'Tidak Berisiko Psikososial' => 'bg-emerald-500 text-white border-emerald-400',
                                default => 'bg-rose-500 text-white border-rose-400',
                            };
                        @endphp
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg border {{ $riskBg }}">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>
                    @else
                        <div class="w-12 h-12 rounded-2xl bg-white/10 border border-white/10 flex items-center justify-center text-indigo-300">
                            N/A
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Row Utama: Grafik & Detail Intervensi BK --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Bagian Kiri: Grafik Historis Siswa (Col-span 2) --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm lg:col-span-2 flex flex-col justify-between">
                <div class="flex items-center justify-between border-b border-slate-50 pb-3">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Grafik Perkembangan Skor Berkala</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Tren nilai SDQ (perilaku), PSC-17 (psikososial), dan SAS-SV (gadget)</p>
                    </div>
                </div>

                <div class="my-6" style="height: 280px;">
                    @if(count($chartLabels) > 0)
                        <canvas id="studentPersonalTrendChart"></canvas>
                    @else
                        <div class="h-full flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-12 h-12 text-slate-200 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                            </svg>
                            <p class="text-xs">Siswa ini belum pernah menyelesaikan pengisian kuesioner</p>
                        </div>
                    @endif
                </div>

                <div class="text-[10px] text-slate-600 font-medium flex items-center gap-1.5 bg-slate-50 rounded-xl p-3 border border-slate-100/50">
                    <svg class="w-4 h-4 text-indigo-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zm-1 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    <span><strong>Panduan Interpretasi Cepat:</strong> Skala skor instrumen bervariasi. Kurva menurun mengindikasikan perbaikan kondisi psikologis siswa. Hubungan kenaikan skor gadget (SAS-SV) dan gangguan perilaku (SDQ/PSC-17) mengonfirmasi keterkaitan erat.</span>
                </div>
            </div>

            {{-- Bagian Kanan: Rekomendasi/Catatan Konseling Preventif BK --}}
            <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm flex flex-col justify-between">
                <div class="space-y-4">
                    <div>
                        <h3 class="font-bold text-slate-800 text-sm">Rekomendasi Tindakan Konseling</h3>
                        <p class="text-xs text-slate-500 font-medium mt-1">Intervensi dini terarah sebelum gejala berkembang menjadi gangguan psikologis berat</p>
                    </div>

                    @if($latestResponse)
                        <form action="{{ route('guruBk.siswa.recommendation', $student->id) }}" method="POST" class="space-y-3.5">
                            @csrf
                            <div>
                                <label for="recommendation" class="block text-xs font-semibold text-slate-500 mb-1.5">Catatan Intervensi Guru BK</label>
                                <textarea name="recommendation" id="recommendation" rows="9"
                                          placeholder="Ketik rencana intervensi preventif untuk siswa ini (misal: Konseling tatap muka berkala, pembatasan gawai di sekolah, edukasi manajemen waktu berkelompok)..."
                                          class="w-full px-3.5 py-3 text-xs border border-slate-200 rounded-2xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition text-slate-700 leading-relaxed resize-none">{{ $latestResponse->riskClassification->recommendation ?? '' }}</textarea>
                            </div>
                            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2.5 rounded-xl text-xs shadow-md shadow-indigo-100 transition-all duration-150">
                                Simpan Catatan Intervensi BK
                            </button>
                        </form>
                    @else
                        <div class="p-5 border border-dashed border-slate-200 rounded-2xl text-center text-slate-500 space-y-2 py-12">
                            <svg class="w-10 h-10 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <p class="text-xs font-medium leading-normal text-slate-500">Catatan intervensi hanya dapat diberikan setelah siswa menyelesaikan minimal satu kali kuesioner.</p>
                        </div>
                    @endif
                </div>
                
                @if($latestResponse && isset($latestResponse->riskClassification->updated_at))
                    <div class="text-[10px] text-slate-500 font-semibold text-center mt-4">
                        Pembaruan Terakhir: {{ $latestResponse->riskClassification->updated_at->format('d M Y, H:i') }}
                    </div>
                @endif
            </div>
        </div>

        {{-- 3. Riwayat Lengkap Pengisian Kuesioner Siswa --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="font-bold text-slate-800 text-sm">Riwayat Riil Pengisian Kuesioner</h3>
                    <p class="text-xs text-slate-500 font-medium mt-1">Daftar lengkap pengerjaan instrumen diagnostik 3-in-1</p>
                </div>
                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full">{{ count($tableHistory) }} Assessment</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-4">Tanggal Pengisian</th>
                            <th class="px-5 py-4 text-center">Skor SDQ (Max 40)</th>
                            <th class="px-5 py-4 text-center">Skor PSC-17 (Max 34)</th>
                            <th class="px-5 py-4 text-center">Skor SAS-SV (Max 50)</th>
                            <th class="px-5 py-4">Status Klasifikasi Risiko</th>
                            <th class="px-5 py-4">Detail Skor Sub-Dimensi Klinis</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($tableHistory as $response)
                            @php
                                $classification = $response->riskClassification;
                                $overall = $classification->overall_risk ?? 'Rendah';
                                $badgeColor = match($overall) {
                                    'Tidak Berisiko Psikososial' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    default => 'bg-rose-50 text-rose-700 border-rose-100',
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition" x-data="{ openDetail: false }">
                                <td class="px-5 py-4 font-semibold text-slate-800">
                                    {{ $response->completed_at->format('d M Y, H:i') }} WIB
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-violet-600">
                                    {{ $response->sdq_score }}
                                    <span class="text-[10px] text-slate-400 font-normal">/40</span>
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-emerald-600">
                                    {{ $response->psc17_score }}
                                    <span class="text-[10px] text-slate-400 font-normal">/34</span>
                                </td>
                                <td class="px-5 py-4 text-center font-bold text-sky-600">
                                    {{ $response->sassv_score }}
                                    <span class="text-[10px] text-slate-400 font-normal">/50</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $badgeColor }}">
                                        {{ $overall }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <button @click="openDetail = !openDetail"
                                            class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg transition text-[11px] outline-none">
                                        <span x-text="openDetail ? 'Tutup Detail' : 'Buka Skor Klinis'"></span>
                                        <svg class="w-3.5 h-3.5 transition duration-200" :class="openDetail ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                            {{-- Baris Detail Subdomain Klinis --}}
                            <tr x-show="openDetail" x-transition class="bg-slate-50/50">
                                <td colspan="6" class="px-5 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 text-xs text-slate-600 leading-relaxed">
                                        {{-- SDQ Subscores --}}
                                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-2">
                                            <h4 class="font-bold text-violet-700 border-b border-slate-50 pb-1.5">Klasifikasi Subdomain SDQ</h4>
                                            <ul class="space-y-1">
                                                <li class="flex justify-between">
                                                    <span>Gejala Emosional:</span>
                                                    <span class="font-semibold text-slate-800">{{ $classification->sdq_category ?? 'Normal' }}</span>
                                                </li>
                                                <li class="text-[10px] text-slate-400 italic">
                                                    Mengukur kecemasan, ketakutan, sakit kepala/perut akibat stres, dan kesedihan.
                                                </li>
                                            </ul>
                                        </div>

                                        {{-- PSC-17 Subscores --}}
                                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-2">
                                            <h4 class="font-bold text-emerald-700 border-b border-slate-50 pb-1.5">Klasifikasi Subdomain PSC-17</h4>
                                            <ul class="space-y-1">
                                                <li class="flex justify-between">
                                                    <span>Status Screening:</span>
                                                    <span class="font-semibold text-slate-800">{{ $classification->psc17_category ?? 'Negatif' }}</span>
                                                </li>
                                                <li class="text-[10px] text-slate-400 italic">
                                                    Mengukur indikasi kognitif (internalizing, externalizing, dan perhatian anak).
                                                </li>
                                            </ul>
                                        </div>

                                        {{-- SAS-SV Subscores --}}
                                        <div class="bg-white rounded-xl p-4 border border-slate-100 shadow-sm space-y-2">
                                            <h4 class="font-bold text-sky-700 border-b border-slate-50 pb-1.5">Klasifikasi Subdomain SAS-SV</h4>
                                            <ul class="space-y-1">
                                                <li class="flex justify-between">
                                                    <span>Kecanduan Gawai:</span>
                                                    <span class="font-semibold text-slate-800">{{ $classification->sassv_category ?? 'Tidak Kecanduan' }}</span>
                                                </li>
                                                <li class="text-[10px] text-slate-400 italic">
                                                    Kecenderungan kehilangan kontrol waktu akibat penggunaan gawai berlebih.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-8 text-center text-slate-500 font-medium italic">Siswa ini belum pernah mengisi kuesioner assessment.</td>
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
            @if(count($chartLabels) > 0)
            const ctxPersonal = document.getElementById('studentPersonalTrendChart').getContext('2d');
            new Chart(ctxPersonal, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [
                        {
                            label: 'Skor SDQ (Kesulitan Perilaku)',
                            data: {!! json_encode($chartSdq) !!},
                            borderColor: '#7c3aed',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#7c3aed',
                            pointRadius: 4,
                            tension: 0.2
                        },
                        {
                            label: 'Skor PSC-17 (Psikososial)',
                            data: {!! json_encode($chartPsc) !!},
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#10b981',
                            pointRadius: 4,
                            tension: 0.2
                        },
                        {
                            label: 'Skor SAS-SV (Kecanduan Gadget)',
                            data: {!! json_encode($chartSassv) !!},
                            borderColor: '#0ea5e9',
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointBackgroundColor: '#0ea5e9',
                            pointRadius: 4,
                            tension: 0.2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            min: 0,
                            max: 50,
                            grid: { color: '#f1f5f9' }
                        },
                        x: { grid: { display: false } }
                    },
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 9 } } }
                    }
                }
            });
            @endif
        });
    </script>
    @endpush
</x-guru-bk-layout>
