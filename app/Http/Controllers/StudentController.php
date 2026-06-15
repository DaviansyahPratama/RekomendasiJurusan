<?php

namespace App\Http\Controllers;

use App\Models\Score;
use App\Models\Student;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('scores')->latest()->get();

        return view('students.index', compact('students'));
    }

    public function create()
    {
        return view('students.create', [
            'interestOptions' => RecommendationService::interestOptions(),
            'preferenceOptions' => RecommendationService::preferenceOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'interest' => ['required', 'array', 'min:1'],
            'interest.*' => ['string'],
            'preference' => ['required', 'string', 'max:255'],
            'subjects' => ['required', 'array', 'min:1'],
            'subjects.*' => ['required', 'string', 'max:255'],
            'scores' => ['required', 'array', 'min:1'],
            'scores.*' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $student = Student::create([
            'name' => $validated['name'],
            'interest' => array_values($validated['interest']),
            'preference' => $validated['preference'],
        ]);

        foreach ($validated['subjects'] as $i => $subjectName) {
            Score::create([
                'student_id' => $student->id,
                'subject_name' => $subjectName,
                'score' => (int) $validated['scores'][$i],
            ]);
        }

        return redirect()->route('recommendation.show', ['studentId' => $student->id]);
    }
}
