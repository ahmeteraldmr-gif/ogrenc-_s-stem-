<?php

namespace App\Livewire\Coach;

use App\Models\StudySchedule;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class ScheduleManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $selectedStudent = '';
    public $activeTab = 'schedules'; // schedules veya templates
    
    // Şablondan program oluşturma
    public $showAssignModal = false;
    public $selectedTemplateId = null;
    public $assignToStudentId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingSelectedStudent()
    {
        $this->resetPage();
    }
    
    public function updatingActiveTab()
    {
        $this->resetPage();
        $this->search = '';
        $this->selectedStudent = '';
    }

    public function deleteSchedule($scheduleId)
    {
        $schedule = StudySchedule::where('coach_id', auth()->id())
            ->findOrFail($scheduleId);

        $schedule->delete();

        session()->flash('message', 'Program silindi.');
    }

    public function toggleActive($scheduleId)
    {
        $schedule = StudySchedule::where('coach_id', auth()->id())
            ->findOrFail($scheduleId);

        $schedule->is_active = !$schedule->is_active;
        $schedule->save();

        session()->flash('message', $schedule->is_active ? 'Program aktif edildi.' : 'Program pasif edildi.');
    }
    
    public function openAssignModal($templateId)
    {
        $this->selectedTemplateId = $templateId;
        $this->assignToStudentId = null;
        $this->showAssignModal = true;
    }
    
    public function closeAssignModal()
    {
        $this->showAssignModal = false;
        $this->selectedTemplateId = null;
        $this->assignToStudentId = null;
    }
    
    public function assignTemplateToStudent()
    {
        $this->validate([
            'assignToStudentId' => 'required|exists:users,id',
            'selectedTemplateId' => 'required|exists:study_schedules,id',
        ]);
        
        $template = StudySchedule::where('coach_id', auth()->id())
            ->where('is_template', true)
            ->with('items')
            ->findOrFail($this->selectedTemplateId);
        
        // Şablondan yeni program oluştur
        $newSchedule = StudySchedule::create([
            'coach_id' => auth()->id(),
            'student_id' => $this->assignToStudentId,
            'name' => $template->name . ' - ' . now()->format('d.m.Y'),
            'is_active' => true,
            'is_template' => false,
            'schedule_type' => $template->schedule_type,
        ]);
        
        // Şablon görevlerini kopyala
        foreach ($template->items as $item) {
            $newSchedule->items()->create([
                'day_of_week' => $item->day_of_week,
                'time_slot' => $item->time_slot,
                'course_id' => $item->course_id,
                'topic_id' => $item->topic_id,
                'sub_topic_id' => $item->sub_topic_id,
                'question_count' => $item->question_count,
                'description' => $item->description,
                'order' => $item->order,
            ]);
        }
        
        $this->closeAssignModal();
        session()->flash('message', 'Şablon öğrenciye başarıyla atandı.');
    }

    public function render()
    {
        $query = StudySchedule::where('coach_id', auth()->id())
            ->with(['student', 'items']);
        
        // Tab'a göre filtreleme
        if ($this->activeTab === 'templates') {
            $query->templates();
        } else {
            $query->schedules();
        }

        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%');
                
                if ($this->activeTab === 'schedules') {
                    $q->orWhereHas('student', function($sq) {
                        $sq->where('name', 'like', '%' . $this->search . '%');
                    });
                }
            });
        }

        if ($this->selectedStudent && $this->activeTab === 'schedules') {
            $query->where('student_id', $this->selectedStudent);
        }

        $items = $query->orderBy('created_at', 'desc')->paginate(10);

        // Öğrenci listesi (şablon atama ve filtreleme için)
        $students = auth()->user()->students()->orderBy('name')->get();

        return view('livewire.coach.schedule-management', [
            'items' => $items,
            'students' => $students,
        ]);
    }
}
