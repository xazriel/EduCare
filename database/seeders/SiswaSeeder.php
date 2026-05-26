<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classes;
use App\Models\QuestionnaireResponse;
use App\Models\SdqAnswer;
use App\Models\Psc17Answer;
use App\Models\SassvAnswer;
use App\Models\RiskClassification;

class SiswaSeeder extends Seeder
{
    public function run(): void
    {
        // Buat kelas terlebih dahulu
        $kelas1 = Classes::firstOrCreate(['name' => 'X IPA 1'],  ['grade' => 10, 'academic_year' => '2024/2025']);
        $kelas2 = Classes::firstOrCreate(['name' => 'X IPS 2'],  ['grade' => 10, 'academic_year' => '2024/2025']);
        $kelas3 = Classes::firstOrCreate(['name' => 'XI IPA 3'], ['grade' => 11, 'academic_year' => '2024/2025']);

        // Data siswa dummy
        $siswaList = [
            ['name' => 'Andi Pratama',   'email' => 'andi@siswa.test',   'nis' => '2024001', 'gender' => 'L', 'class_id' => $kelas1->id],
            ['name' => 'Sari Dewi',      'email' => 'sari@siswa.test',   'nis' => '2024002', 'gender' => 'P', 'class_id' => $kelas1->id],
            ['name' => 'Budi Santoso',   'email' => 'budi@siswa.test',   'nis' => '2024003', 'gender' => 'L', 'class_id' => $kelas2->id],
            ['name' => 'Rini Wulandari', 'email' => 'rini@siswa.test',   'nis' => '2024004', 'gender' => 'P', 'class_id' => $kelas2->id],
            ['name' => 'Dika Saputra',   'email' => 'dika@siswa.test',   'nis' => '2024005', 'gender' => 'L', 'class_id' => $kelas3->id],
        ];

        foreach ($siswaList as $data) {
            $siswa = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                    'nis'      => $data['nis'],
                    'gender'   => $data['gender'],
                    'class_id' => $data['class_id'],
                ]
            );

            $siswa->assignRole('siswa');

            // Buat 2 riwayat assessment per siswa
            $this->createAssessment($siswa, now()->subDays(30), 'low');
            $this->createAssessment($siswa, now()->subDays(5),  'medium');
        }

        // Siswa dengan risiko tinggi untuk demo
        $siswaHigh = User::firstOrCreate(
            ['email' => 'rizal@siswa.test'],
            [
                'name'     => 'Rizal Firmansyah',
                'password' => Hash::make('password'),
                'nis'      => '2024006',
                'gender'   => 'L',
                'class_id' => $kelas3->id,
            ]
        );
        $siswaHigh->assignRole('siswa');
        $this->createAssessment($siswaHigh, now()->subDays(15), 'high');
    }

    private function createAssessment(User $user, $date, string $level): void
    {
        [$sdqAnswers, $psc17Answers, $sassvAnswers] = $this->getAnswersByLevel($level, $user->gender);

        $response = QuestionnaireResponse::create([
            'user_id'      => $user->id,
            'sdq_score'    => $sdqAnswers['total'],
            'psc17_score'  => $psc17Answers['total'],
            'sassv_score'  => $sassvAnswers['total'],
            'status'       => 'completed',
            'completed_at' => $date,
            'created_at'   => $date,
            'updated_at'   => $date,
        ]);

        // Simpan jawaban SDQ
        foreach ($sdqAnswers['answers'] as $num => $val) {
            SdqAnswer::create(['response_id' => $response->id, 'question_number' => $num, 'answer_value' => $val]);
        }

        // Simpan jawaban PSC-17
        foreach ($psc17Answers['answers'] as $num => $val) {
            Psc17Answer::create(['response_id' => $response->id, 'question_number' => $num, 'answer_value' => $val]);
        }

        // Simpan jawaban SAS-SV
        foreach ($sassvAnswers['answers'] as $num => $val) {
            SassvAnswer::create(['response_id' => $response->id, 'question_number' => $num, 'answer_value' => $val]);
        }

        // Klasifikasi
        [$sdqCat, $psc17Cat, $sassvCat, $overall, $recommendation] = $this->getClassification($level);

        RiskClassification::create([
            'response_id'    => $response->id,
            'sdq_category'   => $sdqCat,
            'psc17_category' => $psc17Cat,
            'sassv_category' => $sassvCat,
            'overall_risk'   => $overall,
            'recommendation' => $recommendation,
        ]);
    }

    private function getAnswersByLevel(string $level, string $gender): array
    {
        if ($level === 'low') {
            // SDQ rendah (total ~10)
            $sdqAns = array_fill(1, 25, 0);
            foreach ([2,3,5,6,8,10,12,13,15,16] as $n) $sdqAns[$n] = 1;
            // PSC-17 rendah (total ~8)
            $pscAns = array_fill(1, 17, 0);
            foreach ([1,2,6,7,8,11,12,13] as $n) $pscAns[$n] = 1;
            // SAS-SV rendah
            $sasAns = array_fill(1, 10, 2);
            return [
                ['answers' => $sdqAns, 'total' => 10],
                ['answers' => $pscAns, 'total' => 8],
                ['answers' => $sasAns, 'total' => 20],
            ];
        }

        if ($level === 'medium') {
            // SDQ sedang (total ~17)
            $sdqAns = array_fill(1, 25, 1);
            foreach ([2,5,8,10,12,13] as $n) $sdqAns[$n] = 2;
            // PSC-17 sedang (total ~12)
            $pscAns = array_fill(1, 17, 1);
            foreach ([6,7,8] as $n) $pscAns[$n] = 2;
            // SAS-SV sedang
            $sasAns = array_fill(1, 10, 3);
            $sasAns[1] = 4; $sasAns[2] = 4;
            return [
                ['answers' => $sdqAns, 'total' => 17],
                ['answers' => $pscAns, 'total' => 12],
                ['answers' => $sasAns, 'total' => 32],
            ];
        }

        // high
        // SDQ tinggi (total ~25)
        $sdqAns = array_fill(1, 25, 2);
        foreach ([1,4,7,9,11,14,17,20,21,25] as $n) $sdqAns[$n] = 0; // prosocial/reverse
        // PSC-17 tinggi (total ~20)
        $pscAns = array_fill(1, 17, 2);
        foreach ([3,4,15,16,17] as $n) $pscAns[$n] = 1;
        // SAS-SV tinggi
        $sasAns = array_fill(1, 10, 4);
        $sasAns[3] = 5; $sasAns[6] = 5;
        return [
            ['answers' => $sdqAns, 'total' => 25],
            ['answers' => $pscAns, 'total' => 20],
            ['answers' => $sasAns, 'total' => 42],
        ];
    }

    private function getClassification(string $level): array
    {
        return match ($level) {
            'low'    => ['Normal',    'Negatif', 'Tidak Kecanduan', 'Rendah',       'Kondisi psikososialmu tergolong baik! Pertahankan pola penggunaan gadget yang sehat.'],
            'medium' => ['Borderline','Negatif', 'Kecanduan',       'Sedang',       'Disarankan membatasi penggunaan gadget dan berkonsultasi dengan guru BK.'],
            'high'   => ['Abnormal',  'Positif', 'Kecanduan',       'Sangat Tinggi','Segera konsultasikan dengan psikolog atau konselor profesional.'],
            default  => ['Normal',    'Negatif', 'Tidak Kecanduan', 'Rendah',       'Kondisi psikososialmu tergolong baik!'],
        };
    }
}
