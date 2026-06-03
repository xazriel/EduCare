<?php

namespace App\Http\Controllers\GuruBk;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Classes;
use App\Models\QuestionnaireResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GuruBkDashboardController extends Controller
{
    public function index()
    {
        // 1. Ringkasan Statistik
        $totalStudents = User::role('siswa')->count();
        
        $completedStudents = User::role('siswa')->whereHas('questionnaireResponses', function($q) {
            $q->where('status', 'completed');
        })->count();
        
        $nonCompletedStudents = $totalStudents - $completedStudents;
        
        $participationRate = $totalStudents > 0 ? round(($completedStudents / $totalStudents) * 100) : 0;

        // 2. Ringkasan Jumlah Siswa Per Kategori Risiko (Berdasarkan Kuesioner Terakhir)
        $students = User::role('siswa')
            ->with(['questionnaireResponses' => function($q) {
                $q->where('status', 'completed')->latest('completed_at');
            }, 'questionnaireResponses.riskClassification'])
            ->get();

        $riskCounts = [
            'Tidak Berisiko Psikososial' => 0,
            'Berisiko Psikososial' => 0,
        ];
        
        $highRiskStudentsCount = 0;

        foreach ($students as $student) {
            $latest = $student->questionnaireResponses->first();
            if ($latest && $latest->riskClassification) {
                $risk = $latest->riskClassification->overall_risk;
                if (array_key_exists($risk, $riskCounts)) {
                    $riskCounts[$risk]++;
                }
                if ($risk === 'Berisiko Psikososial') {
                    $highRiskStudentsCount++;
                }
            }
        }

        // 3. Grafik Tren Ketergantungan Gawai Bulanan (Rata-rata SAS-SV 6 Bulan Terakhir)
        $isSqlite = config('database.default') === 'sqlite';
        if ($isSqlite) {
            $monthlySassv = QuestionnaireResponse::where('status', 'completed')
                ->selectRaw("strftime('%Y-%m', completed_at) as month, AVG(sassv_score) as avg_score")
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->take(6)
                ->get();
        } else {
            $monthlySassv = QuestionnaireResponse::where('status', 'completed')
                ->selectRaw("DATE_FORMAT(completed_at, '%Y-%m') as month, AVG(sassv_score) as avg_score")
                ->groupBy('month')
                ->orderBy('month', 'asc')
                ->take(6)
                ->get();
        }

        // Format data untuk Chart.js
        $monthlyLabels = [];
        $monthlyScores = [];
        
        // Buat nama bulan ramah Bahasa Indonesia
        $indoMonths = [
            '01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'Mei', '06' => 'Jun',
            '07' => 'Jul', '08' => 'Agt', '09' => 'Sep', '10' => 'Okt', '11' => 'Nov', '12' => 'Des'
        ];

        foreach ($monthlySassv as $data) {
            if ($data->month) {
                $parts = explode('-', $data->month);
                $year = $parts[0] ?? '';
                $monthNum = $parts[1] ?? '';
                $monthLabel = isset($indoMonths[$monthNum]) ? $indoMonths[$monthNum] . ' ' . $year : $data->month;
                $monthlyLabels[] = $monthLabel;
                $monthlyScores[] = round($data->avg_score, 1);
            }
        }

        // 4. Distribusi Status Risiko Siswa Per Kelas & Skor Rata-rata
        $classesData = Classes::with(['students.questionnaireResponses' => function($q) {
            $q->where('status', 'completed')->latest('completed_at');
        }, 'students.questionnaireResponses.riskClassification'])->get()->map(function($class) {
            $totalStudents = $class->students->count();
            $completedCount = 0;
            $sdqSum = 0;
            $pscSum = 0;
            $sassvSum = 0;
            
            $risks = ['Tidak Berisiko Psikososial' => 0, 'Berisiko Psikososial' => 0];
            
            foreach ($class->students as $student) {
                $latest = $student->questionnaireResponses->first();
                if ($latest) {
                    $completedCount++;
                    $sdqSum += $latest->sdq_score;
                    $pscSum += $latest->psc17_score;
                    $sassvSum += $latest->sassv_score;
                    
                    if ($latest->riskClassification) {
                        $risk = $latest->riskClassification->overall_risk;
                        if (isset($risks[$risk])) {
                            $risks[$risk]++;
                        }
                    }
                }
            }
            
            return [
                'id' => $class->id,
                'name' => $class->name,
                'total_students' => $totalStudents,
                'completed_count' => $completedCount,
                'avg_sdq' => $completedCount > 0 ? round($sdqSum / $completedCount, 1) : 0,
                'avg_psc' => $completedCount > 0 ? round($pscSum / $completedCount, 1) : 0,
                'avg_sassv' => $completedCount > 0 ? round($sassvSum / $completedCount, 1) : 0,
                'risks' => $risks,
            ];
        });

        // 5. Daftar Assessment Terbaru (Limit 5)
        $recentAssessments = QuestionnaireResponse::with(['user.studentClass', 'riskClassification'])
            ->where('status', 'completed')
            ->latest('completed_at')
            ->take(5)
            ->get();

        return view('guru_bk.dashboard', compact(
            'totalStudents', 'completedStudents', 'nonCompletedStudents', 'participationRate',
            'riskCounts', 'highRiskStudentsCount', 'monthlyLabels', 'monthlyScores',
            'classesData', 'recentAssessments'
        ));
    }
}