<?php

namespace App\Http\Controllers;

use App\Models\Student;

class ScoreController extends Controller
{
    public function index()
    {
        $students = Student::with('scores')->orderBy('name')->get();

        return view('scores.index', compact('students'));
    }
}
