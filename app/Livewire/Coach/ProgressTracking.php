<?php

namespace App\Livewire\Coach;

use App\Models\AssignmentProgress;
use App\Models\StudentAssignment;
use App\Models\User;
use Livewire\Component;

class ProgressTracking extends Component
{
    public $studentId;
    public $student;
    public $selectedCourseId;
    public $expandedTopics = [];

    public function mount($studentId)
    {
        $this->studentId = $studentId;
        $this->student = User::findOrFail($studentId);
        
        // Bu öğrenci bu koça ait mi kontrol et
        if (!auth()->user()->students()->where('users.id', $studentId)->exists()) {
            abort(403);
        }
    }

    public function toggleTopic($topicId)
    {
        if (in_array($topicId, $this->expandedTopics)) {
            $this->expandedTopics = array_diff($this->expandedTopics, [$topicId]);
        } else {
            $this->expandedTopics[] = $topicId;
        }
    }

    public function toggleProgress($assignmentId)
    {
        $assignment = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->findOrFail($assignmentId);

        $progress = AssignmentProgress::firstOrNew([
            'student_assignment_id' => $assignmentId,
        ]);

        // Sub topic ID'yi de ekliyoruz
        $progress->sub_topic_id = $assignment->sub_topic_id;
        $progress->is_completed = !$progress->is_completed;
        
        if ($progress->is_completed && !$progress->completed_at) {
            $progress->completed_at = now();
        } elseif (!$progress->is_completed) {
            $progress->completed_at = null;
        }

        $progress->save();

        session()->flash('message', $progress->is_completed ? 'Konu tamamlandı olarak işaretlendi.' : 'Tamamlandı işareti kaldırıldı.');
    }

    public function render()
    {
        // Öğrenciye atanan konular ve ilerleme durumu
        $assignmentsQuery = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with(['course.field', 'topic', 'subTopic', 'progress']);

        if ($this->selectedCourseId) {
            $assignmentsQuery->where('course_id', $this->selectedCourseId);
        }

        $assignments = $assignmentsQuery->get()
            ->groupBy('course.field.name');

        // Özet istatistikler
        $totalAssignments = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->count();

        $completedCount = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->whereHas('progress', function($q) {
                $q->where('is_completed', true);
            })
            ->count();

        $completionPercentage = $totalAssignments > 0 
            ? round(($completedCount / $totalAssignments) * 100, 1) 
            : 0;

        // Ders listesi (filtreleme için)
        $courses = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with('course')
            ->get()
            ->pluck('course')
            ->unique('id')
            ->sortBy('name');

        return view('livewire.coach.progress-tracking', [
            'assignments' => $assignments,
            'totalAssignments' => $totalAssignments,
            'completedCount' => $completedCount,
            'completionPercentage' => $completionPercentage,
            'courses' => $courses,
        ]);
    }
}
