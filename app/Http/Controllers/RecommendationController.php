<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function index(Request $request, RecommendationService $service)
    {
        $method = (string) $request->query('method', 'scoring');

        $students = Student::with(['scores', 'recommendations.major'])->orderBy('name')->get();

        $overview = [];
        foreach ($students as $student) {
            $result = $service->recommendForStudent($student, $method);
            $overview[] = [
                'student' => $student,
                'best' => $result['best'],
                'ranked' => $result['ranked'],
            ];
        }

        return view('recommendations.index', [
            'overview' => $overview,
            'method' => $method,
            'methods' => [
                'scoring' => 'Scoring System',
                'decision_tree' => 'Decision Tree',
                'truth_table' => 'Tabel Kebenaran',
                'graph' => 'Graf Relasi',
            ],
        ]);
    }

    public function show(string|int $studentId, Request $request, RecommendationService $service)
    {
        $method = (string) $request->query('method', 'scoring');
        $student = Student::with(['scores', 'recommendations.major'])->findOrFail($studentId);
        $result = $service->recommendForStudent($student, $method);

        return view('recommendations.show', [
            'student' => $student,
            'ranked' => $result['ranked'],
            'best' => $result['best'],
            'method' => $result['method'],
            'truth_table' => $result['truth_table'],
            'relation_graph' => $result['relation_graph'],
            'methods' => [
                'scoring' => 'Scoring System',
                'decision_tree' => 'Decision Tree',
                'truth_table' => 'Tabel Kebenaran',
                'graph' => 'Graf Relasi',
            ],
        ]);
    }
}
