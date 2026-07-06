<?php

namespace App\Livewire\Coach;

use App\Models\AssignmentProgress;
use App\Models\StudentAssignment;
use App\Models\StudentResource;
use App\Models\User;
use Livewire\Component;

class StudentDetail extends Component
{
    public $studentId;
    public $student;
    public $expandedFields = [];
    public $expandedCourses = [];
    public $expandedTopics = [];

    // Not ekleme için
    public $editingNoteFor = null;
    public $noteText = '';

    public $activeTab = 'progress'; // 'progress', 'analysis'
    public $analysisFieldId = '';
    public $analysisCourseId = '';

    public function mount($studentId)
    {
        $this->studentId = $studentId;
        $this->student = User::with(['questionLogs', 'examResults', 'studyLogs'])
            ->findOrFail($studentId);

        // Bu öğrenci bu koça ait mi kontrol et
        if (!auth()->user()->students()->where('users.id', $studentId)->exists()) {
            abort(403);
        }

        // Tab kontrolü
        if (request()->query('tab') === 'analiz') {
            $this->activeTab = 'analysis';
        }
    }

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

    public function startEditingNote($assignmentId)
    {
        $assignment = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with('progress')
            ->findOrFail($assignmentId);

        $this->editingNoteFor = $assignmentId;
        $this->noteText = $assignment->progress?->notes ?? '';
    }

    public function saveNote($assignmentId)
    {
        $assignment = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->findOrFail($assignmentId);

        $progress = AssignmentProgress::firstOrCreate(
            [
                'student_assignment_id' => $assignmentId,
            ],
            [
                'sub_topic_id' => $assignment->sub_topic_id,
                'is_completed' => false,
            ]
        );

        $progress->notes = $this->noteText;
        $progress->save();

        $this->editingNoteFor = null;
        $this->noteText = '';

        session()->flash('message', 'Not kaydedildi.');
    }

    public function cancelNote()
    {
        $this->editingNoteFor = null;
        $this->noteText = '';
    }

    public function render()
    {
        // Öğrenciye atanan konular ve ilerleme durumu
        $assignments = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with(['course.field', 'topic', 'subTopic', 'progress'])
            ->get()
            ->groupBy('course.field.name');

        // Özet istatistikler
        $totalAssignments = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->count();

        $completedCount = StudentAssignment::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->whereHas('progress', function ($q) {
                $q->where('is_completed', true);
            })
            ->count();

        $completionPercentage = $totalAssignments > 0
            ? round(($completedCount / $totalAssignments) * 100, 1)
            : 0;

        // Soru ve deneme istatistikleri
        $totalQuestions = $this->student->questionLogs->sum('total_questions');
        $correctAnswers = $this->student->questionLogs->sum('correct_answers');
        $totalExams = $this->student->examResults->count();
        $avgNet = $this->student->examResults->avg('net_score');

        // Öğrenciye atanan kaynaklar
        $studentResources = StudentResource::where('student_id', $this->studentId)
            ->where('coach_id', auth()->id())
            ->with(['resource', 'course', 'field'])
            ->latest('assigned_at')
            ->get();

        // Group resources by field and course
        $groupedResources = [];
        foreach ($studentResources as $resource) {
            $fieldName = $resource->field ? $resource->field->name : 'Genel';
            $courseName = $resource->course ? $resource->course->name : 'Genel';

            if (!isset($groupedResources[$fieldName])) {
                $groupedResources[$fieldName] = [];
            }
            if (!isset($groupedResources[$fieldName][$courseName])) {
                $groupedResources[$fieldName][$courseName] = [];
            }
            $groupedResources[$fieldName][$courseName][] = $resource;
        }

        // Analysis Logic
        // Analysis Logic & Filters (Synced with student assignments)
        $analysisFields = \App\Models\Field::whereHas('courses.studentAssignments', function ($q) {
            $q->where('student_id', $this->studentId);
        })->get();

        $analysisCourses = collect();
        if ($this->analysisFieldId) {
            $analysisCourses = \App\Models\Course::where('field_id', $this->analysisFieldId)
                ->whereHas('studentAssignments', function ($q) {
                    $q->where('student_id', $this->studentId);
                })->get();
        }

        $analysis = $this->getWeaknessAnalysis();
        $examStats = $this->getExamStatsByField();

        return view('livewire.coach.student-detail', [
            'assignments' => $assignments,
            'totalAssignments' => $totalAssignments,
            'completedCount' => $completedCount,
            'completionPercentage' => $completionPercentage,
            'totalQuestions' => $totalQuestions,
            'correctAnswers' => $correctAnswers,
            'totalExams' => $totalExams,
            'avgNet' => $avgNet,
            'studentResources' => $studentResources,
            'groupedResources' => $groupedResources,
            'analysis' => $analysis,
            'examStats' => $examStats,
            'analysisFields' => $analysisFields,
            'analysisCourses' => $analysisCourses,
        ]);
    }

    public function updatedAnalysisFieldId()
    {
        $this->analysisCourseId = '';
    }

    protected function getWeaknessAnalysis()
    {
        $query = \App\Models\QuestionLog::where('student_id', $this->studentId)
            ->with(['course', 'course.field', 'topic']);

        if ($this->analysisFieldId) {
            $query->whereHas('course', function ($q) {
                $q->where('field_id', $this->analysisFieldId);
            });
        }

        if ($this->analysisCourseId) {
            $query->where('course_id', $this->analysisCourseId);
        }

        $topicStats = $query->selectRaw('course_id, topic_id, SUM(total_questions) as total, SUM(correct_answers) as correct')
            ->groupBy('course_id', 'topic_id')
            ->havingRaw('SUM(total_questions) > 0')
            ->get()
            ->map(function ($log) {
                $log->success_rate = round(($log->correct / $log->total) * 100, 1);
                $log->field_name = $log->course->field->name ?? 'Genel';

                // Gelişim treni hesapla (son 3 log vs genel ortalama)
                $recentLogs = \App\Models\QuestionLog::where('student_id', $this->studentId)
                    ->where('topic_id', $log->topic_id)
                    ->latest('log_date')
                    ->take(3)
                    ->get();

                if ($recentLogs->count() >= 2) {
                    $recentTotal = $recentLogs->sum('total_questions');
                    $recentCorrect = $recentLogs->sum('correct_answers');
                    $recentRate = $recentTotal > 0 ? ($recentCorrect / $recentTotal) * 100 : 0;

                    if ($recentRate > $log->success_rate + 5) {
                        $log->trend = 'up';
                    } elseif ($recentRate < $log->success_rate - 5) {
                        $log->trend = 'down';
                    } else {
                        $log->trend = 'stable';
                    }
                } else {
                    $log->trend = 'new';
                }

                return $log;
            });

        return [
            'critical' => $topicStats->filter(fn($s) => $s->success_rate < 60 && $s->total >= 5),
            'at_risk' => $topicStats->filter(fn($s) => $s->success_rate >= 60 && $s->success_rate < 75),
            'strong' => $topicStats->filter(fn($s) => $s->success_rate >= 75),
            'all' => $topicStats->sortBy('success_rate'),
        ];
    }

    protected function getExamStatsByField()
    {
        $query = \App\Models\ExamResult::where('student_id', $this->studentId)
            ->with('field');

        if ($this->analysisFieldId) {
            $query->where('field_id', $this->analysisFieldId);
        }

        return $query->selectRaw('field_id, AVG(net_score) as avg_net, MAX(net_score) as max_net, COUNT(*) as exam_count')
            ->groupBy('field_id')
            ->get();
    }
}

