<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;

class GuruBkBiController extends Controller
{
    public function index()
    {
        // 1. Analisis Per Gender (Risiko Berdasarkan Kuesioner Terakhir)
        $students = User::role('siswa')
            ->with(['questionnaireResponses' => function($q) {
                $q->where('status', 'completed')->latest('completed_at');
            }, 'questionnaireResponses.riskClassification'])
            ->get();

        $genderRisks = [
            'L' => ['Tidak Berisiko Psikososial' => 0, 'Berisiko Psikososial' => 0],
            'P' => ['Tidak Berisiko Psikososial' => 0, 'Berisiko Psikososial' => 0]
        ];

        foreach ($students as $student) {
            $latest = $student->questionnaireResponses->first();
            $g = ($student->gender === 'L' || $student->gender === 'l') ? 'L' : 'P';
            if ($latest && $latest->riskClassification) {
                $risk = $latest->riskClassification->overall_risk;
                if (isset($genderRisks[$g][$risk])) {
                    $genderRisks[$g][$risk]++;
                }
            }
        }

        // 2. Rata-rata Skor per Kelas
        $classes = Classes::with(['students.questionnaireResponses' => function($q) {
            $q->where('status', 'completed')->latest('completed_at');
        }])->get();

        $classLabels = [];
        $classSdq = [];
        $classPsc = [];
        $classSassv = [];

        foreach ($classes as $class) {
            $classLabels[] = $class->name;
            $completedCount = 0;
            $sdqSum = 0;
            $pscSum = 0;
            $sassvSum = 0;

            foreach ($class->students as $student) {
                $latest = $student->questionnaireResponses->first();
                if ($latest) {
                    $completedCount++;
                    $sdqSum += $latest->sdq_score;
                    $pscSum += $latest->psc17_score;
                    $sassvSum += $latest->sassv_score;
                }
            }

            $classSdq[] = $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0;
            $classPsc[] = $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0;
            $classSassv[] = $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0;
        }

        // 3. Tren Perkembangan Psikososial Bulanan Sekolah (SDQ & PSC-17 6 Bulan Terakhir)
        $isSqlite = config('database.default') === 'sqlite';
        if ($isSqlite) {
            $monthlyTrend = QuestionnaireResponse::where('status', 'completed')
                ->selectRaw("strftime('%Y-%m', completed_at) as month, AVG(sdq_score) as avg_sdq, AVG(psc17_score) as avg_psc, AVG(sassv_score) as avg_sassv")
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->take(6)
                ->get();
        } else {
            $monthlyTrend = QuestionnaireResponse::where('status', 'completed')
                ->selectRaw("DATE_FORMAT(completed_at, '%Y-%m') as month, AVG(sdq_score) as avg_sdq, AVG(psc17_score) as avg_psc, AVG(sassv_score) as avg_sassv")
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->take(6)
                ->get();
        }

        $trendLabels = [];
        $trendSdq = [];
        $trendPsc = [];
        $trendSassv = [];
        
        $indoMonths = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        foreach ($monthlyTrend as $data) {
            if ($data->month) {
                $parts = explode('-', $data->month);
                $year = $parts[0] ?? '';
                $monthNum = $parts[1] ?? '';
                $monthLabel = isset($indoMonths[$monthNum]) ? $indoMonths[$monthNum] . ' ' . $year : $data->month;
                
                $trendLabels[] = $monthLabel;
                $trendSdq[] = round($data->avg_sdq, 1);
                $trendPsc[] = round($data->avg_psc, 1);
                $trendSassv[] = round($data->avg_sassv, 1);
            }
        }

        // 4. Analisis Korelasi Skor Antara SAS-SV dengan SDQ & PSC-17
        $responses = QuestionnaireResponse::with('user')
            ->where('status', 'completed')
            ->orderBy('completed_at', 'desc')
            ->take(100)
            ->get();

        $correlationPoints = $responses->map(function($resp) {
            return [
                'x' => $resp->sassv_score,       // Kecanduan Gawai
                'y_sdq' => $resp->sdq_score,     // Risiko Perilaku (SDQ)
                'y_psc' => $resp->psc17_score,   // Risiko Psikososial (PSC-17)
                'name' => $resp->user->name ?? 'Siswa',
            ];
        })->values()->toArray();

        return view('guru_bk.bi', compact(
            'genderRisks', 
            'classLabels', 'classSdq', 'classPsc', 'classSassv',
            'trendLabels', 'trendSdq', 'trendPsc', 'trendSassv',
            'correlationPoints'
        ));
    }
}
