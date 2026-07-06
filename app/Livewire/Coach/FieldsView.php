<?php

namespace App\Livewire\Coach;

use App\Models\Field;
use Livewire\Component;

class FieldsView extends Component
{
    public $expandedFields = [];
    public $expandedCourses = [];
    public $expandedTopics = [];

    public function toggleField($fieldId)
    {
        if (in_array($fieldId, $this->expandedFields)) {
            $this->expandedFields = array_diff($this->expandedFields, [$fieldId]);
        } else {
            $this->expandedFields[] = $fieldId;
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
        $fields = Field::with(['courses.topics.subTopics'])
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return view('livewire.coach.fields-view', [
            'fields' => $fields,
        ]);
    }
}

