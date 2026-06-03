<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\QuestionnaireResponse;
use App\Models\RiskClassification;
use Illuminate\Http\Request;

class GuruBkSiswaController extends Controller
{
    public function index(Request $request)
    {
        $classes = Classes::orderBy('name')->get();

        $query = User::role('siswa')->with([
            'studentClass', 
            'questionnaireResponses' => function($q) {
                $q->where('status', 'completed')->latest('completed_at');
            }, 
            'questionnaireResponses.riskClassification'
        ]);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nis', 'like', "%{$search}%");
            });
        }

        if ($request->filled('class_id')) {
            $query->where('class_id', $request->input('class_id'));
        }

        if ($request->filled('gender')) {
            $query->where('gender', $request->input('gender'));
        }

        $students = $query->get();

        // Penyaringan tingkat risiko terbaru berbasis PHP Collection
        if ($request->filled('risk_level')) {
            $risk = $request->input('risk_level');
            $students = $students->filter(function($student) use ($risk) {
                $latest = $student->questionnaireResponses->first();
                if ($latest && $latest->riskClassification) {
                    return $latest->riskClassification->overall_risk === $risk;
                }
                return false;
            })->values();
        }

        return view('guru_bk.siswa.index', compact('students', 'classes'));
    }

    public function show($id)
    {
        $student = User::role('siswa')->with('studentClass')->findOrFail($id);

        // Kuesioner riwayat lengkap (urut tanggal terlama ke terbaru untuk grafik)
        $history = QuestionnaireResponse::with('riskClassification')
            ->where('user_id', $id)
            ->where('status', 'completed')
            ->orderBy('completed_at', 'asc')
            ->get();

        // Kuesioner riwayat terbalik (untuk tabel riwayat, terbaru di atas)
        $tableHistory = $history->reverse()->values();

        // Grafik linear pribadi
        $chartLabels = [];
        $chartSdq = [];
        $chartPsc = [];
        $chartSassv = [];

        foreach ($history as $response) {
            $chartLabels[] = $response->completed_at->format('d M Y');
            $chartSdq[] = $response->sdq_score;
            $chartPsc[] = $response->psc17_score;
            $chartSassv[] = $response->sassv_score;
        }

        $latestResponse = $tableHistory->first();

        return view('guru_bk.siswa.show', compact(
            'student', 'history', 'tableHistory', 'latestResponse',
            'chartLabels', 'chartSdq', 'chartPsc', 'chartSassv'
        ));
    }

    public function storeRecommendation(Request $request, $id)
    {
        $request->validate([
            'recommendation' => 'required|string|max:1000',
        ]);

        $latestResponse = QuestionnaireResponse::where('user_id', $id)
            ->where('status', 'completed')
            ->latest('completed_at')
            ->first();

        if (!$latestResponse) {
            return redirect()->back()->with('error', 'Siswa belum menyelesaikan kuesioner apapun.');
        }

        $classification = RiskClassification::firstOrNew(['response_id' => $latestResponse->id]);
        $classification->recommendation = $request->input('recommendation');
        $classification->save();

        return redirect()->back()->with('success', 'Rekomendasi tindakan preventif berhasil disimpan.');
    }
}
