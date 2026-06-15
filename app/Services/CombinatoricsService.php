<?php

namespace App\Services;

use App\Models\Student;

class CombinatoricsService
{
    public function nCr(int $n, int $r): int
    {
        if ($r < 0 || $r > $n) {
            return 0;
        }

        $r = min($r, $n - $r);

        $num = 1;
        $den = 1;
        for ($i = 1; $i <= $r; $i++) {
            $num *= ($n - ($r - $i));
            $den *= $i;
        }

        return intdiv($num, $den);
    }

    /**
     * @return array<int, array<int, int>>
     */
    public function combinationsFromN(int $n, int $r): array
    {
        if ($n <= 0 || $r < 0 || $r > $n) {
            return [];
        }

        return $this->combinations(range(1, $n), $r);
    }

    /**
     * Generate student group combinations using real student names when available.
     *
     * @return array{n: int, r: int, nCr: int, combinations: array<int, array<int, string>>}
     */
    public function studentGroupCombinations(int $n, int $r, ?array $labels = null): array
    {
        $raw = $this->combinationsFromN($n, $r);
        $labels = $labels ?? array_map(fn ($i) => "M{$i}", range(1, $n));

        $named = [];
        foreach ($raw as $combo) {
            $named[] = array_map(fn ($idx) => $labels[$idx - 1] ?? "M{$idx}", $combo);
        }

        return [
            'n' => $n,
            'r' => $r,
            'nCr' => $this->nCr($n, $r),
            'combinations' => $named,
        ];
    }

    /**
     * @return array{n: int, team_sizes: array<int, int>, count: int, formations: array<int, array{teams: array<int, array<int, int>>}>}
     */
    public function possibleGroupFormations(int $n, array $teamSizes): array
    {
        $teamSizes = array_values(array_unique(array_filter(
            array_map('intval', $teamSizes),
            fn ($x) => $x > 0
        )));
        sort($teamSizes);

        $students = range(1, $n);
        $formations = [];
        $this->backtrackFormations($students, [], $teamSizes, $formations);

        return [
            'n' => $n,
            'team_sizes' => $teamSizes,
            'count' => count($formations),
            'formations' => $formations,
        ];
    }

    private function backtrackFormations(array $remaining, array $currentTeams, array $teamSizes, array &$formations): void
    {
        if (count($remaining) === 0) {
            $formations[] = ['teams' => $currentTeams];

            return;
        }

        foreach ($teamSizes as $size) {
            if ($size > count($remaining)) {
                continue;
            }

            foreach ($this->combinations($remaining, $size) as $combo) {
                $comboSet = array_flip($combo);
                $nextRemaining = array_values(array_filter(
                    $remaining,
                    fn ($s) => ! isset($comboSet[$s])
                ));

                $nextTeams = $currentTeams;
                $nextTeams[] = $combo;

                $this->backtrackFormations($nextRemaining, $nextTeams, $teamSizes, $formations);
            }
        }
    }

    /**
     * @return array<int, array<int, int>>
     */
    private function combinations(array $items, int $r): array
    {
        $n = count($items);
        if ($r === 0) {
            return [[]];
        }
        if ($r === 1) {
            return array_map(fn ($x) => [$x], $items);
        }
        if ($r > $n) {
            return [];
        }

        $result = [];
        $this->combinationDfs($items, $r, 0, [], $result);

        return $result;
    }

    private function combinationDfs(array $items, int $r, int $start, array $path, array &$result): void
    {
        if (count($path) === $r) {
            $result[] = $path;

            return;
        }

        for ($i = $start; $i < count($items); $i++) {
            $newPath = $path;
            $newPath[] = $items[$i];
            $this->combinationDfs($items, $r, $i + 1, $newPath, $result);
        }
    }
}
