<?php

namespace App\Services;

use App\Models\Major;
use App\Models\Recommendation;
use App\Models\Student;

class RecommendationService
{
    /** @var array<string, list<string>> */
    private const INTEREST_MAJOR_MAP = [
        'Web' => ['Teknik Informatika'],
        'Data Science' => ['Data Science'],
        'AI' => ['Artificial Intelligence (AI)'],
        'Jaringan' => ['Jaringan Komputer'],
    ];

    /** @var array<string, list<string>> */
    private const PREFERENCE_MAJOR_MAP = [
        'Analisis' => ['Data Science', 'Artificial Intelligence (AI)'],
        'Praktik' => ['Teknik Informatika', 'Jaringan Komputer'],
        'Desain' => ['Teknik Informatika'],
    ];

    /**
     * @return array{ranked: array<int, array>, best: ?array, method: string, truth_table: ?array, relation_graph: ?array}
     */
    public function recommendForStudent(Student $student, string $method = 'scoring'): array
    {
        $method = in_array($method, ['scoring', 'decision_tree', 'truth_table', 'graph'], true)
            ? $method
            : 'scoring';

        $interest = $student->interest ?? [];
        if (! is_array($interest)) {
            $interest = [$interest];
        }

        $preference = (string) $student->preference;
        $avg = (float) ($student->scores->avg('score') ?? 0);

        $ranked = [];
        $truthTable = $method === 'truth_table' ? [] : null;
        $relationGraph = $method === 'graph' ? ['nodes' => [], 'edges' => []] : null;

        foreach (Major::query()->orderBy('name')->get() as $major) {
            $interestMatch = $this->calculateInterestMatch($interest, $major);
            $preferenceMatch = $this->calculatePreferenceMatch($preference, $major);
            $avgPoints = $this->pointsFromAverage($avg);

            $interestPoints = 40 * $interestMatch;
            $preferencePoints = 30 * $preferenceMatch;
            $total = $interestPoints + $preferencePoints + $avgPoints;

            $decisionLines = $this->buildDecisionTreeLines($interestMatch, $preferenceMatch, $avg, $avgPoints, $major->name, $total);

            $ranked[] = [
                'major_id' => $major->id,
                'major_name' => $major->name,
                'score_total' => (int) $total,
                'breakdown' => [
                    'interest_match' => (int) $interestPoints,
                    'preference_match' => (int) $preferencePoints,
                    'average_points' => (int) $avgPoints,
                    'average' => $avg,
                ],
                'explanation' => implode("\n", $decisionLines),
                'decision_tree' => $decisionLines,
            ];

            if ($truthTable !== null) {
                $truthTable[] = $this->buildTruthTableRow($major->name, $interestMatch, $preferenceMatch, $avgPoints, $total);
            }

            if ($relationGraph !== null) {
                $relationGraph['nodes'][] = ['id' => "major-{$major->id}", 'label' => $major->name, 'type' => 'major'];
                $relationGraph['edges'][] = [
                    'from' => "student-{$student->id}",
                    'to' => "major-{$major->id}",
                    'weight' => $total,
                    'label' => "skor {$total}",
                ];
            }

            Recommendation::updateOrCreate(
                ['student_id' => $student->id, 'major_id' => $major->id],
                [
                    'score' => (int) $total,
                    'explanation' => implode("\n", $decisionLines),
                ]
            );
        }

        usort($ranked, fn ($a, $b) => $b['score_total'] <=> $a['score_total']);

        if ($relationGraph !== null) {
            $relationGraph['nodes'][] = [
                'id' => "student-{$student->id}",
                'label' => $student->name,
                'type' => 'student',
            ];
        }

        return [
            'ranked' => $ranked,
            'best' => $ranked[0] ?? null,
            'method' => $method,
            'truth_table' => $truthTable,
            'relation_graph' => $relationGraph,
        ];
    }

    /**
     * @return array<int, string>
     */
    private function buildDecisionTreeLines(
        int $interestMatch,
        int $preferenceMatch,
        float $avg,
        int $avgPoints,
        string $majorName,
        int $total
    ): array {
        $interestPoints = 40 * $interestMatch;
        $preferencePoints = 30 * $preferenceMatch;

        $lines = [];
        $lines[] = $interestMatch
            ? 'IF minat cocok → +40'
            : 'IF minat cocok → +0 (tidak cocok)';
        $lines[] = $preferenceMatch
            ? 'IF preferensi cocok → +30'
            : 'IF preferensi cocok → +0 (tidak cocok)';
        $lines[] = $this->averageDecisionLine($avg, $avgPoints);
        $lines[] = "FINAL RESULT (major={$majorName}) = {$interestPoints}+{$preferencePoints}+{$avgPoints}={$total}";

        return $lines;
    }

    /**
     * @param  list<string>  $studentInterests
     */
    private function calculateInterestMatch(array $studentInterests, Major $major): int
    {
        $majorName = (string) $major->name;

        foreach ($studentInterests as $interest) {
            $interest = trim((string) $interest);
            if ($interest === '') {
                continue;
            }

            $mappedMajors = self::INTEREST_MAJOR_MAP[$interest] ?? [];
            if (in_array($majorName, $mappedMajors, true)) {
                return 1;
            }
        }

        return 0;
    }

    private function calculatePreferenceMatch(string $preference, Major $major): int
    {
        $preference = trim($preference);
        if ($preference === '') {
            return 0;
        }

        $mappedMajors = self::PREFERENCE_MAJOR_MAP[$preference] ?? [];

        return in_array((string) $major->name, $mappedMajors, true) ? 1 : 0;
    }

    private function pointsFromAverage(float $avg): int
    {
        if ($avg >= 85) {
            return 30;
        }

        if ($avg >= 70) {
            return 20;
        }

        return 10;
    }

    private function averageDecisionLine(float $avg, int $bucket): string
    {
        if ($bucket === 30) {
            return 'IF nilai tinggi (>=85) → +30 (avg='.number_format($avg, 2).')';
        }

        if ($bucket === 20) {
            return 'IF nilai sedang (70–84) → +20 (avg='.number_format($avg, 2).')';
        }

        return 'IF nilai rendah (<70) → +10 (avg='.number_format($avg, 2).')';
    }

    /**
     * @return array{major: string, minat: string, preferensi: string, nilai: string, total: int}
     */
    private function buildTruthTableRow(
        string $majorName,
        int $interestMatch,
        int $preferenceMatch,
        int $avgPoints,
        int $total
    ): array {
        return [
            'major' => $majorName,
            'minat' => $interestMatch ? '1 (+40)' : '0 (+0)',
            'preferensi' => $preferenceMatch ? '1 (+30)' : '0 (+0)',
            'nilai' => "+{$avgPoints}",
            'total' => $total,
        ];
    }

    public static function interestOptions(): array
    {
        return array_keys(self::INTEREST_MAJOR_MAP);
    }

    public static function preferenceOptions(): array
    {
        return array_keys(self::PREFERENCE_MAJOR_MAP);
    }
}
