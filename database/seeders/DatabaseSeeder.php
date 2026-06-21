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
            'Pemrograman',
            'Jaringan',
            'Data Science',
            'Artificial Intelligence',
            'Cyber Security',
            'Multimedia',
        ];

        foreach ($minatList as $nama) {
            Minat::create(['nama_minat' => $nama]);
        }

        $mataKuliahData = [
            ['kode_mk' => 'IF101', 'nama_mk' => 'Algoritma dan Pemrograman', 'sks' => 3, 'semester' => 1, 'kategori' => 'Pemrograman', 'tingkat_kesulitan' => 2],
            ['kode_mk' => 'IF102', 'nama_mk' => 'Matematika Diskrit', 'sks' => 3, 'semester' => 1, 'kategori' => 'Pemrograman', 'tingkat_kesulitan' => 2],
            ['kode_mk' => 'IF201', 'nama_mk' => 'Struktur Data', 'sks' => 3, 'semester' => 2, 'kategori' => 'Pemrograman', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF202', 'nama_mk' => 'Basis Data', 'sks' => 3, 'semester' => 2, 'kategori' => 'Pemrograman', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF301', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester' => 3, 'kategori' => 'Pemrograman', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF302', 'nama_mk' => 'Jaringan Komputer', 'sks' => 3, 'semester' => 3, 'kategori' => 'Jaringan', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF303', 'nama_mk' => 'Analisis Data', 'sks' => 3, 'semester' => 3, 'kategori' => 'Data Science', 'tingkat_kesulitan' => 3],
            ['kode_mk' => 'IF401', 'nama_mk' => 'Machine Learning', 'sks' => 3, 'semester' => 4, 'kategori' => 'Data Science', 'tingkat_kesulitan' => 4],
            ['kode_mk' => 'IF402', 'nama_mk' => 'Kecerdasan Buatan', 'sks' => 3, 'semester' => 4, 'kategori' => 'Artificial Intelligence', 'tingkat_kesulitan' => 4],
            ['kode_mk' => 'IF403', 'nama_mk' => 'Keamanan Siber', 'sks' => 3, 'semester' => 4, 'kategori' => 'Cyber Security', 'tingkat_kesulitan' => 4],
            ['kode_mk' => 'IF404', 'nama_mk' => 'Desain Multimedia', 'sks' => 3, 'semester' => 4, 'kategori' => 'Multimedia', 'tingkat_kesulitan' => 2],
            ['kode_mk' => 'IF501', 'nama_mk' => 'Deep Learning', 'sks' => 3, 'semester' => 5, 'kategori' => 'Artificial Intelligence', 'tingkat_kesulitan' => 5],
            ['kode_mk' => 'IF502', 'nama_mk' => 'Administrasi Jaringan', 'sks' => 3, 'semester' => 5, 'kategori' => 'Jaringan', 'tingkat_kesulitan' => 4],
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
