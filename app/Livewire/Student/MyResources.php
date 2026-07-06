<?php

namespace App\Livewire\Student;

use App\Models\StudentResource;
use Livewire\Component;

class MyResources extends Component
{
    public $filterCourse = null;
    public $groupBy = 'course'; // course, coach, none

    public function render()
    {
        $query = StudentResource::where('student_id', auth()->id())
                                ->with(['resource', 'course', 'coach']);

        // Ders filtreleme
        if ($this->filterCourse) {
            $query->where('course_id', $this->filterCourse);
        }

        $assignments = $query->latest('assigned_at')->get();

        // Gruplama
        $groupedAssignments = null;
        if ($this->groupBy === 'course') {
            $groupedAssignments = $assignments->groupBy('course_id');
        } elseif ($this->groupBy === 'coach') {
            $groupedAssignments = $assignments->groupBy('coach_id');
        }

        // Ders listesi (filtreleme için)
        $courses = $assignments->pluck('course')->filter()->unique('id')->sortBy('name');

        return view('livewire.student.my-resources', [
            'assignments' => $assignments,
            'groupedAssignments' => $groupedAssignments,
            'courses' => $courses,
        ]);
    }
}

