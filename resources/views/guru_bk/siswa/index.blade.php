<x-guru-bk-layout pageTitle="Manajemen & Monitoring Siswa" pageSubtitle="Cari siswa, pantau status psikososial secara individu, dan rancang konseling preventif.">

    <div class="space-y-6">
        {{-- 1. Panel Penyaringan / Filter --}}
        <div class="bg-white rounded-2xl p-5 border border-slate-100 shadow-sm">
            <form action="{{ route('guruBk.siswa.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                {{-- Pencarian --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-semibold text-slate-500 mb-1.5">Pencarian Siswa</label>
                    <div class="relative">
                        <input type="text" name="search" id="search" value="{{ request('search') }}"
                               placeholder="Cari Nama Siswa atau NIS..."
                               class="w-full pl-10 pr-4 py-2 text-xs border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition text-slate-700">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                                <circle cx="11" cy="11" r="8"></circle>
                                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filter Kelas --}}
                <div>
                    <label for="class_id" class="block text-xs font-semibold text-slate-500 mb-1.5">Pilih Kelas</label>
                    <select name="class_id" id="class_id"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition text-slate-700">
                        <option value="">Semua Kelas</option>
                        @foreach($classes as $class)
                            <option value="{{ $class->id }}" {{ request('class_id') == $class->id ? 'selected' : '' }}>
                                {{ $class->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Filter Risiko --}}
                <div>
                    <label for="risk_level" class="block text-xs font-semibold text-slate-500 mb-1.5">Tingkat Risiko</label>
                    <select name="risk_level" id="risk_level"
                            class="w-full px-3 py-2 text-xs border border-slate-200 rounded-xl focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition text-slate-700">
                        <option value="">Semua Risiko</option>
                        <option value="Tidak Berisiko Psikososial" {{ request('risk_level') === 'Tidak Berisiko Psikososial' ? 'selected' : '' }}>Tidak Berisiko Psikososial</option>
                        <option value="Berisiko Psikososial" {{ request('risk_level') === 'Berisiko Psikososial' ? 'selected' : '' }}>Berisiko Psikososial</option>
                    </select>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex items-end gap-2">
                    <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-xl text-xs shadow-sm transition-all duration-150">
                        Saring Data
                    </button>
                    @if(request()->anyFilled(['search', 'class_id', 'risk_level', 'gender']))
                        <a href="{{ route('guruBk.siswa.index') }}" class="px-3 py-2 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition text-center">
                            Reset
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- 2. Daftar Siswa --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 text-slate-600 font-bold border-b border-slate-100">
                            <th class="px-5 py-4">Nama Lengkap</th>
                            <th class="px-5 py-4">NIS</th>
                            <th class="px-5 py-4">Kelas</th>
                            <th class="px-5 py-4">Gender</th>
                            <th class="px-5 py-4 text-center">Partisipasi Kuesioner</th>
                            <th class="px-5 py-4">Status Risiko Terakhir</th>
                            <th class="px-5 py-4 text-right">Rencana Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700">
                        @forelse($students as $student)
                            @php
                                $latestResponse = $student->questionnaireResponses->first();
                                $overallRisk = $latestResponse->riskClassification->overall_risk ?? null;
                                
                                $riskBadge = match($overallRisk) {
                                    'Tidak Berisiko Psikososial' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'Berisiko Psikososial'       => 'bg-rose-50 text-rose-700 border-rose-100 animate-pulse',
                                    default => null,
                                };
                            @endphp
                            <tr class="hover:bg-slate-50/50 transition">
                                {{-- Nama Siswa --}}
                                <td class="px-5 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                            {{ strtoupper(substr($student->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-bold text-slate-800">{{ $student->name }}</p>
                                            <p class="text-[10px] text-slate-500 font-medium mt-0.5">{{ $student->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                
                                {{-- NIS --}}
                                <td class="px-5 py-4 font-mono text-slate-500">{{ $student->nis ?? '-' }}</td>
                                
                                {{-- Kelas --}}
                                <td class="px-5 py-4 font-semibold text-slate-700">{{ $student->studentClass->name ?? 'Belum Diatur' }}</td>
                                
                                {{-- Gender --}}
                                <td class="px-5 py-4">
                                    @if($student->gender === 'L' || $student->gender === 'l')
                                        <span class="px-2 py-0.5 rounded bg-sky-50 text-sky-700 font-medium text-[10px] border border-sky-100">Laki-laki</span>
                                    @else
                                        <span class="px-2 py-0.5 rounded bg-pink-50 text-pink-700 font-medium text-[10px] border border-pink-100">Perempuan</span>
                                    @endif
                                </td>
                                
                                {{-- Jumlah Assessment --}}
                                <td class="px-5 py-4 text-center">
                                    @php $responseCount = $student->questionnaireResponses->count(); @endphp
                                    @if($responseCount > 0)
                                        <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 rounded-full font-bold text-[10px]">
                                            {{ $responseCount }} Selesai
                                        </span>
                                    @else
                                        <span class="text-slate-500 font-semibold italic text-[11px]">Belum Berpartisipasi</span>
                                    @endif
                                </td>

                                {{-- Risiko Terakhir --}}
                                <td class="px-5 py-4">
                                    @if($overallRisk)
                                        <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold border {{ $riskBadge }}">
                                            {{ $overallRisk }}
                                        </span>
                                    @else
                                        <span class="px-2 py-0.5 bg-slate-50 text-slate-500 border border-slate-200 rounded text-[10px] font-bold">
                                            N/A
                                        </span>
                                    @endif
                                </td>
                                
                                {{-- Aksi --}}
                                <td class="px-5 py-4 text-right">
                                    <a href="{{ route('guruBk.siswa.show', $student->id) }}"
                                       class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-bold transition text-[11px] shadow-sm">
                                        Monitor & Rencana Konseling
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-12 text-center text-slate-500 font-medium italic">
                                    Siswa tidak ditemukan atau belum ada data kuesioner terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-guru-bk-layout>
