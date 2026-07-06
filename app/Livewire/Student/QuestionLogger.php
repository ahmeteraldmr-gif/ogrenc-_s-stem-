<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\QuestionLog;
use App\Models\Topic;
use App\Models\SubTopic;
use Livewire\Component;
use Livewire\WithPagination;

class QuestionLogger extends Component
{
    use WithPagination;

    public $showModal = false;
    public $course_id;
    public $topic_id;
    public $sub_topic_id;
    public $total_questions;
    public $correct_answers;
    public $wrong_answers;
    public $blank_answers = 0;
    public $log_date;
    public $notes;
    
    public $topics = [];
    public $subTopics = [];

    protected $rules = [
        'course_id' => 'nullable|exists:courses,id',
        'topic_id' => 'nullable|exists:topics,id',
        'sub_topic_id' => 'nullable|exists:sub_topics,id',
        'total_questions' => 'required|integer|min:1',
        'correct_answers' => 'required|integer|min:0',
        'wrong_answers' => 'required|integer|min:0',
        'log_date' => 'required|date',
        'notes' => 'nullable|string',
    ];

    public function mount()
    {
        $this->log_date = now()->format('Y-m-d');
    }

    public function updatedCourseId($value)
    {
        $this->topics = [];
        $this->subTopics = [];
        $this->topic_id = null;
        $this->sub_topic_id = null;

        if ($value) {
            $this->topics = Topic::where('course_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedTopicId($value)
    {
        $this->subTopics = [];
        $this->sub_topic_id = null;

        if ($value) {
            $this->subTopics = SubTopic::where('topic_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['total_questions', 'correct_answers', 'wrong_answers'])) {
            $this->calculateBlank();
        }
    }

    public function calculateBlank()
    {
        $total = (int) $this->total_questions;
        $correct = (int) $this->correct_answers;
        $wrong = (int) $this->wrong_answers;
        
        $this->blank_answers = max(0, $total - $correct - $wrong);
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['course_id', 'topic_id', 'sub_topic_id', 'total_questions', 'correct_answers', 'wrong_answers', 'blank_answers', 'notes']);
        $this->topics = [];
        $this->subTopics = [];
        $this->log_date = now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        QuestionLog::create([
            'student_id' => auth()->id(),
            'course_id' => $this->course_id,
            'topic_id' => $this->topic_id,
            'sub_topic_id' => $this->sub_topic_id,
            'total_questions' => $this->total_questions,
            'correct_answers' => $this->correct_answers,
            'wrong_answers' => $this->wrong_answers,
            'blank_answers' => $this->blank_answers,
            'log_date' => $this->log_date,
            'notes' => $this->notes,
        ]);

        session()->flash('message', 'Soru kaydınız başarıyla eklendi.');
        $this->closeModal();
    }

    public function delete($id)
    {
        QuestionLog::where('student_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('message', 'Kayıt silindi.');
    }

    public function render()
    {
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        
        $questionLogs = QuestionLog::where('student_id', auth()->id())
            ->with(['course', 'topic', 'subTopic'])
            ->latest('log_date')
            ->paginate(10);

        $stats = [
            'total_questions' => QuestionLog::where('student_id', auth()->id())->sum('total_questions'),
            'total_correct' => QuestionLog::where('student_id', auth()->id())->sum('correct_answers'),
            'total_wrong' => QuestionLog::where('student_id', auth()->id())->sum('wrong_answers'),
            'total_blank' => QuestionLog::where('student_id', auth()->id())->sum('blank_answers'),
        ];

        if ($stats['total_questions'] > 0) {
            $stats['success_rate'] = round(($stats['total_correct'] / $stats['total_questions']) * 100, 1);
        } else {
            $stats['success_rate'] = 0;
        }

        return view('livewire.student.question-logger', [
            'courses' => $courses,
            'questionLogs' => $questionLogs,
            'stats' => $stats,
        ]);
    }
}
