<?php

namespace Database\Seeders;

use App\Models\Major;
use App\Models\Recommendation;
use App\Models\Score;
use App\Models\Student;
use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name' => 'Admin Demo',
            'email' => 'admin@demo.test',
        ]);

        Recommendation::query()->delete();
        Score::query()->delete();
        Student::query()->delete();
        Major::query()->delete();

        $majors = [
            [
                'name' => 'Teknik Informatika',
                'description' => 'Pengembangan perangkat lunak, web development, dan rekayasa perangkat lunak.',
            ],
            [
                'name' => 'Data Science',
                'description' => 'Analisis data, statistik, machine learning, dan pengambilan keputusan berbasis data.',
            ],
            [
                'name' => 'Artificial Intelligence (AI)',
                'description' => 'Kecerdasan buatan, deep learning, dan sistem cerdas untuk prediksi dan klasifikasi.',
            ],
            [
                'name' => 'Jaringan Komputer',
                'description' => 'Konsep jaringan, routing, keamanan jaringan, dan komunikasi data.',
            ],
        ];

        foreach ($majors as $major) {
            Major::create($major);
        }

        $students = [
            ['name' => 'Aulia Rahma', 'interest' => ['Web'], 'preference' => 'Desain', 'base' => 88],
            ['name' => 'Bima Santoso', 'interest' => ['Data Science'], 'preference' => 'Analisis', 'base' => 76],
            ['name' => 'Citra Dewi', 'interest' => ['AI'], 'preference' => 'Analisis', 'base' => 91],
            ['name' => 'Dimas Pratama', 'interest' => ['Jaringan'], 'preference' => 'Praktik', 'base' => 68],
            ['name' => 'Eka Putri', 'interest' => ['Data Science', 'AI'], 'preference' => 'Analisis', 'base' => 82],
            ['name' => 'Fajar Nugroho', 'interest' => ['Web'], 'preference' => 'Praktik', 'base' => 74],
            ['name' => 'Gita Lestari', 'interest' => ['Jaringan'], 'preference' => 'Praktik', 'base' => 86],
            ['name' => 'Hadi Wijaya', 'interest' => ['AI'], 'preference' => 'Analisis', 'base' => 79],
        ];

        $subjectPool = [
            'Matematika Diskrit',
            'Struktur Data',
            'Pemrograman Web',
            'Machine Learning',
            'Jaringan Komputer',
            'Kecerdasan Buatan',
            'Basis Data',
            'Analisis Data',
            'UI/UX Design',
            'Keamanan Siber',
        ];

        $service = app(RecommendationService::class);

        foreach ($students as $idx => $data) {
            $student = Student::create([
                'name' => $data['name'],
                'interest' => $data['interest'],
                'preference' => $data['preference'],
            ]);

            $countSubjects = [3, 4, 4, 3, 5, 4, 3, 4][$idx] ?? 3;
            $picked = array_slice($subjectPool, $idx, $countSubjects);
            if (count($picked) < $countSubjects) {
                $picked = array_slice(array_merge($picked, $subjectPool), 0, $countSubjects);
            }

            foreach ($picked as $j => $subjectName) {
                $delta = [4, -6, 2, -3, 5, -2, 1, -4][$j] ?? ($j % 2 === 0 ? 3 : -3);
                Score::create([
                    'student_id' => $student->id,
                    'subject_name' => $subjectName,
                    'score' => max(0, min(100, $data['base'] + $delta)),
                ]);
            }

            $service->recommendForStudent($student->fresh('scores'));
        }
    }
}
