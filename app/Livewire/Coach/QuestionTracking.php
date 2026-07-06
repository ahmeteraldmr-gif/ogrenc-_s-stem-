<?php

namespace App\Livewire\Coach;

use Livewire\Component;
use Livewire\WithPagination;

class QuestionTracking extends Component
{
    use WithPagination;

    public $selectedStudent;
    public $selectedCourse;
    public $selectedTopic;
    public $selectedSubTopic;
    public $dateFrom;
    public $dateTo;
    public $viewMode = 'list'; // 'list' or 'grouped'
    
    public $courses = [];
    public $topics = [];
    public $subTopics = [];

    public function mount()
    {
        $this->courses = \App\Models\Course::where('is_active', true)->orderBy('name')->get();
    }

    public function updatedSelectedCourse($value)
    {
        $this->topics = [];
        $this->subTopics = [];
        $this->selectedTopic = null;
        $this->selectedSubTopic = null;
        $this->resetPage();

        if ($value) {
            $this->topics = \App\Models\Topic::where('course_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function updatedSelectedTopic($value)
    {
        $this->subTopics = [];
        $this->selectedSubTopic = null;
        $this->resetPage();

        if ($value) {
            $this->subTopics = \App\Models\SubTopic::where('topic_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->get();
        }
    }

    public function applyFilters()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->selectedStudent = null;
        $this->selectedCourse = null;
        $this->selectedTopic = null;
        $this->selectedSubTopic = null;
        $this->dateFrom = null;
        $this->dateTo = null;
        $this->topics = [];
        $this->subTopics = [];
        $this->resetPage();
    }

    public function render()
    {
        $students = auth()->user()->students;

        $query = \App\Models\QuestionLog::whereIn('student_id', $students->pluck('id'));

        // Apply filters
        if ($this->selectedStudent) {
            $query->where('student_id', $this->selectedStudent);
        }

        if ($this->selectedCourse) {
            $query->where('course_id', $this->selectedCourse);
        }

        if ($this->selectedTopic) {
            $query->where('topic_id', $this->selectedTopic);
        }

        if ($this->selectedSubTopic) {
            $query->where('sub_topic_id', $this->selectedSubTopic);
        }

        if ($this->dateFrom) {
            $query->whereDate('log_date', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('log_date', '<=', $this->dateTo);
        }

        $questionLogs = $query->with(['student', 'course', 'topic', 'subTopic'])
            ->latest('log_date')
            ->paginate(15);

        // Calculate statistics
        $allLogs = \App\Models\QuestionLog::whereIn('student_id', $students->pluck('id'));
        if ($this->selectedStudent) {
            $allLogs->where('student_id', $this->selectedStudent);
        }
        
        $stats = [
            'total_questions' => $allLogs->sum('total_questions'),
            'total_correct' => $allLogs->sum('correct_answers'),
            'total_wrong' => $allLogs->sum('wrong_answers'),
            'total_blank' => $allLogs->sum('blank_answers'),
        ];

        if ($stats['total_questions'] > 0) {
            $stats['success_rate'] = round(($stats['total_correct'] / $stats['total_questions']) * 100, 1);
        } else {
            $stats['success_rate'] = 0;
        }

        // Grouped data for hierarchical view
        $groupedData = null;
        if ($this->viewMode === 'grouped') {
            $groupedData = $this->getGroupedData();
        }

        // Chart data preparation
        $chartData = $this->prepareChartData($students);

        return view('livewire.coach.question-tracking', [
            'questionLogs' => $questionLogs,
            'students' => $students,
            'stats' => $stats,
            'groupedData' => $groupedData,
            'chartData' => $chartData,
        ]);
    }
    
    protected function prepareChartData($students)
    {
        $query = \App\Models\QuestionLog::whereIn('student_id', $students->pluck('id'))
            ->with(['course', 'topic', 'subTopic']);
        
        // Apply same filters as main query
        if ($this->selectedStudent) {
            $query->where('student_id', $this->selectedStudent);
        }
        if ($this->selectedCourse) {
            $query->where('course_id', $this->selectedCourse);
        }
        if ($this->selectedTopic) {
            $query->where('topic_id', $this->selectedTopic);
        }
        if ($this->selectedSubTopic) {
            $query->where('sub_topic_id', $this->selectedSubTopic);
        }
        if ($this->dateFrom) {
            $query->whereDate('log_date', '>=', $this->dateFrom);
        }
        if ($this->dateTo) {
            $query->whereDate('log_date', '<=', $this->dateTo);
        }
        
        $allLogs = $query->orderBy('log_date', 'asc')->get();
        
        // 1. Günlük Soru Çözüm Grafiği (Zaman İçinde Toplam Soru)
        $dailyProgressData = [
            'labels' => [],
            'totalQuestions' => [],
            'correctAnswers' => [],
            'successRate' => [],
        ];
        
        $dailyData = [];
        foreach ($allLogs as $log) {
            $dateKey = $log->log_date->format('Y-m-d');
            $dateLabel = $log->log_date->format('d.m.Y');
            
            if (!isset($dailyData[$dateKey])) {
                $dailyData[$dateKey] = [
                    'label' => $dateLabel,
                    'total' => 0,
                    'correct' => 0,
                ];
            }
            
            $dailyData[$dateKey]['total'] += $log->total_questions;
            $dailyData[$dateKey]['correct'] += $log->correct_answers;
        }
        
        foreach ($dailyData as $data) {
            $dailyProgressData['labels'][] = $data['label'];
            $dailyProgressData['totalQuestions'][] = $data['total'];
            $dailyProgressData['correctAnswers'][] = $data['correct'];
            $successRate = $data['total'] > 0 ? round(($data['correct'] / $data['total']) * 100, 1) : 0;
            $dailyProgressData['successRate'][] = $successRate;
        }
        
        // 2. Konu Bazlı Gelişim (İlk vs Son Kayıt)
        $topicProgressData = [
            'labels' => [],
            'firstSuccessRate' => [],
            'lastSuccessRate' => [],
            'improvement' => [],
        ];
        
        $topicsData = [];
        foreach ($allLogs as $log) {
            if (!$log->topic) continue;
            
            $topicId = $log->topic_id;
            $topicName = $log->topic->name;
            
            if (!isset($topicsData[$topicId])) {
                $topicsData[$topicId] = [
                    'name' => $topicName,
                    'firstLog' => null,
                    'lastLog' => null,
                    'firstDate' => null,
                    'lastDate' => null,
                ];
            }
            
            // İlk kayıt
            if (is_null($topicsData[$topicId]['firstLog']) || 
                $log->log_date < $topicsData[$topicId]['firstDate']) {
                $topicsData[$topicId]['firstLog'] = $log;
                $topicsData[$topicId]['firstDate'] = $log->log_date;
            }
            
            // Son kayıt
            if (is_null($topicsData[$topicId]['lastLog']) || 
                $log->log_date > $topicsData[$topicId]['lastDate']) {
                $topicsData[$topicId]['lastLog'] = $log;
                $topicsData[$topicId]['lastDate'] = $log->log_date;
            }
        }
        
        foreach ($topicsData as $topicData) {
            if ($topicData['firstLog'] && $topicData['lastLog']) {
                $firstRate = $topicData['firstLog']->total_questions > 0 
                    ? round(($topicData['firstLog']->correct_answers / $topicData['firstLog']->total_questions) * 100, 1)
                    : 0;
                $lastRate = $topicData['lastLog']->total_questions > 0
                    ? round(($topicData['lastLog']->correct_answers / $topicData['lastLog']->total_questions) * 100, 1)
                    : 0;
                
                $topicProgressData['labels'][] = $topicData['name'];
                $topicProgressData['firstSuccessRate'][] = $firstRate;
                $topicProgressData['lastSuccessRate'][] = $lastRate;
                $topicProgressData['improvement'][] = round($lastRate - $firstRate, 1);
            }
        }
        
        // 3. Ders Bazlı Performans
        $coursePerformanceData = [
            'labels' => [],
            'totalQuestions' => [],
            'correctAnswers' => [],
            'successRate' => [],
        ];
        
        $coursesData = [];
        foreach ($allLogs as $log) {
            if (!$log->course) continue;
            
            $courseId = $log->course_id;
            $courseName = $log->course->name;
            
            if (!isset($coursesData[$courseId])) {
                $coursesData[$courseId] = [
                    'name' => $courseName,
                    'total' => 0,
                    'correct' => 0,
                ];
            }
            
            $coursesData[$courseId]['total'] += $log->total_questions;
            $coursesData[$courseId]['correct'] += $log->correct_answers;
        }
        
        foreach ($coursesData as $courseData) {
            $coursePerformanceData['labels'][] = $courseData['name'];
            $coursePerformanceData['totalQuestions'][] = $courseData['total'];
            $coursePerformanceData['correctAnswers'][] = $courseData['correct'];
            $successRate = $courseData['total'] > 0 
                ? round(($courseData['correct'] / $courseData['total']) * 100, 1)
                : 0;
            $coursePerformanceData['successRate'][] = $successRate;
        }
        
        // 4. Aylık Ortalama Başarı Oranı
        $monthlyAverageData = [
            'labels' => [],
            'successRate' => [],
        ];
        
        $monthlyData = [];
        foreach ($allLogs as $log) {
            $monthKey = $log->log_date->format('Y-m');
            $monthLabel = $log->log_date->format('M Y');
            
            if (!isset($monthlyData[$monthKey])) {
                $monthlyData[$monthKey] = [
                    'label' => $monthLabel,
                    'total' => 0,
                    'correct' => 0,
                ];
            }
            
            $monthlyData[$monthKey]['total'] += $log->total_questions;
            $monthlyData[$monthKey]['correct'] += $log->correct_answers;
        }
        
        foreach ($monthlyData as $data) {
            $monthlyAverageData['labels'][] = $data['label'];
            $successRate = $data['total'] > 0 
                ? round(($data['correct'] / $data['total']) * 100, 1)
                : 0;
            $monthlyAverageData['successRate'][] = $successRate;
        }
        
        return [
            'dailyProgress' => $dailyProgressData,
            'topicProgress' => $topicProgressData,
            'coursePerformance' => $coursePerformanceData,
            'monthlyAverage' => $monthlyAverageData,
        ];
    }

    protected function getGroupedData()
    {
        $students = auth()->user()->students;
        $query = \App\Models\QuestionLog::whereIn('student_id', $students->pluck('id'))
            ->with(['course', 'topic', 'subTopic']);

        if ($this->selectedStudent) {
            $query->where('student_id', $this->selectedStudent);
        }

        $logs = $query->get();

        // Group by course > topic > subtopic
        $grouped = [];
        foreach ($logs as $log) {
            if (!$log->course) continue;

            $courseId = $log->course_id;
            $courseName = $log->course->name;

            if (!isset($grouped[$courseId])) {
                $grouped[$courseId] = [
                    'name' => $courseName,
                    'total_questions' => 0,
                    'correct_answers' => 0,
                    'topics' => [],
                ];
            }

            $grouped[$courseId]['total_questions'] += $log->total_questions;
            $grouped[$courseId]['correct_answers'] += $log->correct_answers;

            if ($log->topic) {
                $topicId = $log->topic_id;
                $topicName = $log->topic->name;

                if (!isset($grouped[$courseId]['topics'][$topicId])) {
                    $grouped[$courseId]['topics'][$topicId] = [
                        'name' => $topicName,
                        'total_questions' => 0,
                        'correct_answers' => 0,
                        'subtopics' => [],
                    ];
                }

                $grouped[$courseId]['topics'][$topicId]['total_questions'] += $log->total_questions;
                $grouped[$courseId]['topics'][$topicId]['correct_answers'] += $log->correct_answers;

                if ($log->subTopic) {
                    $subTopicId = $log->sub_topic_id;
                    $subTopicName = $log->subTopic->name;

                    if (!isset($grouped[$courseId]['topics'][$topicId]['subtopics'][$subTopicId])) {
                        $grouped[$courseId]['topics'][$topicId]['subtopics'][$subTopicId] = [
                            'name' => $subTopicName,
                            'total_questions' => 0,
                            'correct_answers' => 0,
                        ];
                    }

                    $grouped[$courseId]['topics'][$topicId]['subtopics'][$subTopicId]['total_questions'] += $log->total_questions;
                    $grouped[$courseId]['topics'][$topicId]['subtopics'][$subTopicId]['correct_answers'] += $log->correct_answers;
                }
            }
        }

        return $grouped;
    }
}
