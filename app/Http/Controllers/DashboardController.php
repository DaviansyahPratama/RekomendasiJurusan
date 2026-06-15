<?php

namespace App\Http\Controllers;

use App\Models\Major;
use App\Models\Recommendation;
use App\Models\Score;
use App\Models\Student;

class DashboardController extends Controller
{
    public function index()
    {
        $studentCount = Student::count();
        $majorCount = Major::count();
        $scoreCount = Score::count();
        $recommendationCount = Recommendation::count();

        $recentStudents = Student::with('scores')
            ->latest()
            ->take(5)
            ->get();

        $complexity = match (true) {
            $studentCount >= 10 => 'luas',
            $studentCount >= 5 => 'menengah',
            default => 'sederhana',
        };

        return view('dashboard.index', compact(
            'studentCount',
            'majorCount',
            'scoreCount',
            'recommendationCount',
            'recentStudents',
            'complexity'
        ));
    }
}
