<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Services\CombinatoricsService;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function combinations(Request $request, CombinatoricsService $service)
    {
        $studentCount = Student::count();
        $n = max(1, (int) $request->query('n', min(5, max(3, $studentCount ?: 3))));
        $r = max(1, min($n, (int) $request->query('r', 2)));

        $teamSizesRaw = (string) $request->query('team_sizes', '2,3');
        $teamSizes = array_values(array_filter(array_map('trim', explode(',', $teamSizesRaw))));
        $teamSizes = array_map('intval', $teamSizes);

        $studentNames = Student::query()->orderBy('name')->pluck('name')->take($n)->all();
        if (count($studentNames) < $n) {
            $studentNames = array_merge(
                $studentNames,
                array_map(fn ($i) => "Mahasiswa {$i}", range(count($studentNames) + 1, $n))
            );
        }

        $combi = $service->studentGroupCombinations($n, $r, $studentNames);
        $result = $service->possibleGroupFormations($n, $teamSizes);

        return view('groups.combinations', [
            'result' => $result,
            'n' => $n,
            'teamSizes' => $teamSizes,
            'r' => $r,
            'nCr' => $combi['nCr'],
            'combi' => $combi,
            'studentCount' => $studentCount,
        ]);
    }
}
