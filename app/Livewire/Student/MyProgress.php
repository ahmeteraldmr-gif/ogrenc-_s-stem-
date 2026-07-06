<?php

namespace App\Livewire\Student;

use Livewire\Component;

class MyProgress extends Component
{
    public function render()
    {
        $student = auth()->user();

        $stats = [
            'total_questions' => $student->questionLogs->sum('total_questions') ?? 0,
            'total_correct' => $student->questionLogs->sum('correct_answers') ?? 0,
            'total_exams' => $student->examResults->count() ?? 0,
            'avg_net' => $student->examResults->avg('net_score') ?? 0,
            'study_days' => $student->studyLogs->count() ?? 0,
        ];

        $recentQuestions = $student->questionLogs()
            ->with('course')
            ->latest('log_date')
            ->take(5)
            ->get();

        $recentExams = $student->examResults()
            ->with('course')
            ->latest('exam_date')
            ->take(5)
            ->get();

        return view('livewire.student.my-progress', [
            'stats' => $stats,
            'recentQuestions' => $recentQuestions,
            'recentExams' => $recentExams,
        ]);
    }
}
