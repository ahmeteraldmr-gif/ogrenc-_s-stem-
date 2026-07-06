<?php

namespace App\Livewire\Student;

use App\Models\StudentAssignment;
use Livewire\Component;

class AssignedCourses extends Component
{
    public $expandedFields = [];
    public $expandedCourses = [];
    public $expandedTopics = [];

    public function toggleField($fieldName)
    {
        if (in_array($fieldName, $this->expandedFields)) {
            $this->expandedFields = array_diff($this->expandedFields, [$fieldName]);
        } else {
            $this->expandedFields[] = $fieldName;
        }
    }

    public function toggleCourse($courseId)
    {
        if (in_array($courseId, $this->expandedCourses)) {
            $this->expandedCourses = array_diff($this->expandedCourses, [$courseId]);
        } else {
            $this->expandedCourses[] = $courseId;
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

    public function render()
    {
        // Öğrenciye atanan tüm konular
        $assignments = StudentAssignment::where('student_id', auth()->id())
            ->with(['course.field', 'topic', 'subTopic', 'progress'])
            ->get()
            ->groupBy('course.field.name');

        // İstatistikler
        $totalAssignments = StudentAssignment::where('student_id', auth()->id())->count();
        $completedCount = StudentAssignment::where('student_id', auth()->id())
            ->whereHas('progress', function($q) {
                $q->where('is_completed', true);
            })
            ->count();
        $completionPercentage = $totalAssignments > 0 
            ? round(($completedCount / $totalAssignments) * 100, 1) 
            : 0;

        return view('livewire.student.assigned-courses', [
            'assignments' => $assignments,
            'totalAssignments' => $totalAssignments,
            'completedCount' => $completedCount,
            'completionPercentage' => $completionPercentage,
        ]);
    }
}

