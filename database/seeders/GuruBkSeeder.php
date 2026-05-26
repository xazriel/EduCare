<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Classes;

class GuruBkSeeder extends Seeder
{
    public function run(): void
    {
        $guruList = [
            [
                'name'     => 'Budi Wahyono, S.Pd',
                'email'    => 'budi.guru@educare.com',
                'nip'      => 'BK-001',
            ],
            [
                'name'     => 'Siti Rahayu, M.Psi',
                'email'    => 'siti.guru@educare.com',
                'nip'      => 'BK-002',
            ],
        ];

        foreach ($guruList as $data) {
            $guru = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'     => $data['name'],
                    'password' => Hash::make('password'),
                    'nis'      => $data['nip'], // gunakan field nis untuk NIP sementara
                    'gender'   => 'L',
                ]
            );

            $guru->assignRole('guru_bk');
        }
    }
}
