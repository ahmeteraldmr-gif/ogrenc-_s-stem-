<?php

namespace App\Livewire\Student;

use App\Models\StudyLog;
use App\Models\SubTopic;
use App\Models\Topic;
use Livewire\Component;
use Livewire\WithPagination;

class StudyLogger extends Component
{
    use WithPagination;

    public $showModal = false;
    public $topic_id;
    public $sub_topic_id;
    public $resource_type = 'video';
    public $resource_title;
    public $description;
    public $study_date;

    protected $rules = [
        'topic_id' => 'nullable|exists:topics,id',
        'sub_topic_id' => 'nullable|exists:sub_topics,id',
        'resource_type' => 'nullable|string',
        'resource_title' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'study_date' => 'required|date',
    ];

    public function mount()
    {
        $this->study_date = now()->format('Y-m-d');
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
        $this->reset(['topic_id', 'sub_topic_id', 'resource_title', 'description']);
        $this->resource_type = 'video';
        $this->study_date = now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        StudyLog::create([
            'student_id' => auth()->id(),
            'topic_id' => $this->topic_id,
            'sub_topic_id' => $this->sub_topic_id,
            'resource_type' => $this->resource_type,
            'resource_title' => $this->resource_title,
            'description' => $this->description,
            'study_date' => $this->study_date,
        ]);

        session()->flash('message', 'Çalışma kaydınız başarıyla eklendi.');
        $this->closeModal();
    }

    public function delete($id)
    {
        StudyLog::where('student_id', auth()->id())->findOrFail($id)->delete();
        session()->flash('message', 'Kayıt silindi.');
    }

    public function render()
    {
        $topics = Topic::where('is_active', true)->orderBy('name')->get();
        $subTopics = SubTopic::where('is_active', true)->orderBy('name')->get();
        
        $studyLogs = StudyLog::where('student_id', auth()->id())
            ->with(['topic', 'subTopic'])
            ->latest('study_date')
            ->paginate(10);

        return view('livewire.student.study-logger', [
            'topics' => $topics,
            'subTopics' => $subTopics,
            'studyLogs' => $studyLogs,
        ]);
    }
}
