<?php

namespace App\Livewire\Coach;

use App\Models\QuestionLog;
use Livewire\Component;

class Dashboard extends Component
{
    public $selectedFieldId = '';
    public $selectedCourseId = '';

    public function render()
    {
        $coach = auth()->user();
        $students = $coach->students;
        $studentIds = $students->pluck('id');

        // Fetch filter data (Synced with student assignments)
        $fields = \App\Models\Field::whereHas('courses.studentAssignments', function ($q) use ($studentIds) {
            $q->whereIn('student_id', $studentIds);
        })->get();

        $courses = collect();
        if ($this->selectedFieldId) {
            $courses = \App\Models\Course::where('field_id', $this->selectedFieldId)
                ->whereHas('studentAssignments', function ($q) use ($studentIds) {
                    $q->whereIn('student_id', $studentIds);
                })->get();
        }

        // Identify critical weaknesses
        $query = QuestionLog::whereIn('student_id', $studentIds)
            ->with(['student', 'course', 'course.field', 'topic']);

        if ($this->selectedFieldId) {
            $query->whereHas('course', function ($q) {
                $q->where('field_id', $this->selectedFieldId);
            });
        }

        if ($this->selectedCourseId) {
            $query->where('course_id', $this->selectedCourseId);
        }

        $criticalAlerts = $query->selectRaw('student_id, course_id, topic_id, SUM(total_questions) as total, SUM(correct_answers) as correct')
            ->groupBy('student_id', 'course_id', 'topic_id')
            ->havingRaw('SUM(total_questions) >= 5')
            ->get()
            ->filter(function ($log) {
                $rate = $log->total > 0 ? ($log->correct / $log->total) * 100 : 0;
                return $rate < 60;
            })
            ->sortBy('student_id')
            ->take(12);

        return view('livewire.coach.dashboard', [
            'students' => $students,
            'criticalAlerts' => $criticalAlerts,
            'fields' => $fields,
            'courses' => $courses,
        ]);
    }

    public function updatedSelectedFieldId()
    {
        $this->selectedCourseId = '';
    }
}
