<?php

namespace Database\Seeders;

use App\Models\MataKuliah;
use App\Models\Minat;
use App\Models\NilaiMahasiswa;
use App\Models\Prasyarat;
use App\Models\User;
use App\Services\RekomendasiMataKuliahService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->delete();

        User::create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $minatList = [
            'Rekayasa Perangkat Lunak',
            'Kecerdasan Buatan dan Data',
            'Jaringan dan Keamanan Siber',
        ];

        foreach ($minatList as $nama) {
            Minat::create(['nama_minat' => $nama]);
        }

        $mataKuliahData = [
            ['kode_mk' => 'IF101', 'nama_mk' => 'Bengkel Pemrograman Framework', 'sks' => 3, 'semester' => 8, 'kategori' => 'Rekayasa Perangkat Lunak', 'tingkat_kesulitan' => 2],
            ['kode_mk' => 'IF102', 'nama_mk' => 'Pemrograman Berorientasi Objek Lanjut', 'sks' => 3, 'semester' => 8, 'kategori' => 'Rekayasa Perangkat Lunak', 'tingkat_kesulitan' => 2],
            ['kode_mk' => 'IF201', 'nama_mk' => 'Pemrograman Game', 'sks' => 3, 'semester' => 8, 'kategori' => 'Rekayasa Perangkat Lunak', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF202', 'nama_mk' => 'Arsitektur Perangkat Lunak & Design Pattern', 'sks' => 3, 'semester' => 8, 'kategori' => 'Rekayasa Perangkat Lunak', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF301', 'nama_mk' => 'Mobile Application Development', 'sks' => 3, 'semester' => 8, 'kategori' => 'Rekayasa Perangkat Lunak', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF302', 'nama_mk' => 'Data Warehouse', 'sks' => 3, 'semester' => 8, 'kategori' => 'Kecerdasan Buatan dan Data', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF303', 'nama_mk' => 'Machine Learning', 'sks' => 3, 'semester' => 8, 'kategori' => 'Kecerdasan Buatan dan Data', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF401', 'nama_mk' => 'Digital Image Processing', 'sks' => 3, 'semester' => 8, 'kategori' => 'Kecerdasan Buatan dan Data', 'tingkat_kesulitan' => 4],
            ['kode_mk' => 'IF402', 'nama_mk' => 'Pattern Recognition', 'sks' => 3, 'semester' => 8, 'kategori' => 'Kecerdasan Buatan dan Data', 'tingkat_kesulitan' => 4],
            ['kode_mk' => 'IF403', 'nama_mk' => 'Natural Language Processing', 'sks' => 3, 'semester' => 8, 'kategori' => 'Kecerdasan Buatan dan Data', 'tingkat_kesulitan' => 4],
            ['kode_mk' => 'IF404', 'nama_mk' => 'Keamanan Jaringan', 'sks' => 3, 'semester' => 8, 'kategori' => 'Jaringan dan Keamanan Siber', 'tingkat_kesulitan' => 2],
            ['kode_mk' => 'IF501', 'nama_mk' => 'Digital Forensics / Network Forensics', 'sks' => 3, 'semester' => 8, 'kategori' => 'Jaringan dan Keamanan Siber', 'tingkat_kesulitan' => 5],
            ['kode_mk' => 'IF502', 'nama_mk' => 'Cloud Computing & Virtualization', 'sks' => 3, 'semester' => 8, 'kategori' => 'Jaringan dan Keamanan Siber', 'tingkat_kesulitan' => 4],
            ['kode_mk' => 'IF503', 'nama_mk' => 'Pemrograman IoT (Internet of Things)', 'sks' => 3, 'semester' => 8, 'kategori' => 'Jaringan dan Keamanan Siber', 'tingkat_kesulitan' => 4],
        ];

        foreach ($mataKuliahData as $data) {
            MataKuliah::create([...$data, 'deskripsi' => 'Mata kuliah '.$data['nama_mk']]);
        }

        $prasyaratMap = [
            'IF201' => ['IF101'],
            'IF202' => ['IF101'],
            'IF301' => ['IF201'],
            'IF302' => ['IF201'],
            'IF303' => ['IF202'],
            'IF401' => ['IF303'],
            'IF402' => ['IF201', 'IF303'],
            'IF403' => ['IF302'],
            'IF404' => ['IF301'],
            'IF501' => ['IF401', 'IF402'],
            'IF502' => ['IF302'],
        ];

        foreach ($prasyaratMap as $kode => $prasyaratKodes) {
            $mk = MataKuliah::where('kode_mk', $kode)->first();
            foreach ($prasyaratKodes as $pKode) {
                $prasyarat = MataKuliah::where('kode_mk', $pKode)->first();
                Prasyarat::create(['mata_kuliah_id' => $mk->id, 'prasyarat_id' => $prasyarat->id]);
            }
        }

        $mahasiswaData = [
            ['name' => 'Aulia Rahma', 'email' => 'aulia@demo.test', 'nim' => '2201001', 'semester_aktif' => 4, 'ipk' => 3.45, 'minat' => ['Pemrograman', 'Multimedia']],
            ['name' => 'Bima Santoso', 'email' => 'bima@demo.test', 'nim' => '2201002', 'semester_aktif' => 4, 'ipk' => 3.12, 'minat' => ['Data Science']],
            ['name' => 'Citra Dewi', 'email' => 'citra@demo.test', 'nim' => '2201003', 'semester_aktif' => 5, 'ipk' => 3.67, 'minat' => ['Artificial Intelligence', 'Data Science']],
        ];

        $service = app(RekomendasiMataKuliahService::class);

        foreach ($mahasiswaData as $data) {
            $minatIds = Minat::whereIn('nama_minat', $data['minat'])->pluck('id');
            unset($data['minat']);

            $user = User::create([
                ...$data,
                'password' => Hash::make('password'),
                'role' => 'mahasiswa',
            ]);

            $user->minat()->sync($minatIds);

            $nilaiRecords = [
                ['kode' => 'IF101', 'nilai' => 88, 'semester' => 1],
                ['kode' => 'IF102', 'nilai' => 82, 'semester' => 1],
                ['kode' => 'IF201', 'nilai' => 85, 'semester' => 2],
                ['kode' => 'IF202', 'nilai' => 78, 'semester' => 2],
            ];

            if ($user->semester_aktif >= 3) {
                $nilaiRecords[] = ['kode' => 'IF301', 'nilai' => 90, 'semester' => 3];
                $nilaiRecords[] = ['kode' => 'IF302', 'nilai' => 75, 'semester' => 3];
                $nilaiRecords[] = ['kode' => 'IF303', 'nilai' => 86, 'semester' => 3];
            }

            if ($user->semester_aktif >= 4) {
                $nilaiRecords[] = ['kode' => 'IF401', 'nilai' => 92, 'semester' => 4];
            }

            foreach ($nilaiRecords as $n) {
                $mk = MataKuliah::where('kode_mk', $n['kode'])->first();
                if ($n['semester'] <= $user->semester_aktif) {
                    NilaiMahasiswa::create([
                        'user_id' => $user->id,
                        'mata_kuliah_id' => $mk->id,
                        'nilai_angka' => $n['nilai'],
                        'grade' => NilaiMahasiswa::hitungGrade($n['nilai']),
                        'semester_lulus' => $n['semester'],
                    ]);
                }
            }

            $service->rekomendasikan($user->fresh(['minat', 'nilaiMahasiswa.mataKuliah']));
        }
    }
}
