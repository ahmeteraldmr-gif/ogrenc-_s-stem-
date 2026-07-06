<?php

namespace App\Livewire\Student;

use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Field;
use Livewire\Component;
use Livewire\WithPagination;

class ExamLogger extends Component
{
    use WithPagination;

    public $showModal = false;
    public $exam_name;
    public $exam_type;
    public $field_id;
    public $exam_date;
    public $notes;
    
    public $fields = [];
    public $filteredCourses = [];
    public $courseResults = [];
    public $examTypes = ['TYT', 'AYT', 'Deneme', 'Deneme-1', 'Deneme-2'];
    public $compareExamA = '';
    public $compareExamB = '';
    
    public $activeTab = 'TYT'; // 'TYT', 'AYT', 'Tümü'
    public $expandedExamKey = null; // format: "exam_name|exam_date"

    public function selectTab($tab)
    {
        $this->activeTab = $tab;
        $this->compareExamA = '';
        $this->compareExamB = '';
        $this->expandedExamKey = null;
        $this->resetPage();
    }

    public function toggleExam($key)
    {
        if ($this->expandedExamKey === $key) {
            $this->expandedExamKey = null;
        } else {
            $this->expandedExamKey = $key;
        }
    }

    public function getExamDetails($examName, $examDate)
    {
        $dateFormatted = is_string($examDate) ? \Carbon\Carbon::parse($examDate)->format('Y-m-d') : $examDate->format('Y-m-d');
        return ExamResult::where('student_id', auth()->id())
            ->where('exam_name', $examName)
            ->whereDate('exam_date', $dateFormatted)
            ->with('course')
            ->get();
    }

    protected function rules()
    {
        return [
            'exam_name' => 'required|string|max:255',
            'exam_type' => 'nullable|string|max:255',
            'field_id' => 'required|exists:fields,id',
            'exam_date' => 'required|date',
            'notes' => 'nullable|string',
            'courseResults.*.correct' => 'nullable|integer|min:0',
            'courseResults.*.wrong' => 'nullable|integer|min:0',
            'courseResults.*.blank' => 'nullable|integer|min:0',
        ];
    }

    protected function validationAttributes()
    {
        $attributes = [];
        foreach ($this->filteredCourses as $course) {
            $attributes["courseResults.{$course->id}.correct"] = "{$course->name} Doğru";
            $attributes["courseResults.{$course->id}.wrong"] = "{$course->name} Yanlış";
            $attributes["courseResults.{$course->id}.blank"] = "{$course->name} Boş";
        }
        return $attributes;
    }

    public function mount()
    {
        $this->exam_date = now()->format('Y-m-d');
        $this->fields = Field::courseFields()
            ->where('is_active', true)
            ->orderBy('order')
            ->get();
    }

    public function updatedFieldId($value)
    {
        $this->courseResults = [];
        $this->filteredCourses = [];

        if ($value) {
            $this->filteredCourses = Course::where('field_id', $value)
                ->where('is_active', true)
                ->orderBy('order')
                ->orderBy('name')
                ->get();
            
            foreach ($this->filteredCourses as $course) {
                $this->courseResults[$course->id] = [
                    'correct' => '',
                    'wrong' => '',
                    'blank' => '',
                    'net' => 0.00,
                ];
            }
        }
    }

    public function updatedCourseResults($value, $key)
    {
        $parts = explode('.', $key);
        if (count($parts) >= 2) {
            $courseId = $parts[0];
            $this->calculateCourseNet($courseId);
        }
    }

    public function calculateCourseNet($courseId)
    {
        if (isset($this->courseResults[$courseId])) {
            $correct = $this->courseResults[$courseId]['correct'];
            $wrong = $this->courseResults[$courseId]['wrong'];
            
            $correctVal = ($correct !== '' && $correct !== null) ? (int) $correct : 0;
            $wrongVal = ($wrong !== '' && $wrong !== null) ? (int) $wrong : 0;
            
            $this->courseResults[$courseId]['net'] = $correctVal - ($wrongVal / 4);
        }
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
        $this->reset(['exam_name', 'exam_type', 'field_id', 'notes', 'courseResults']);
        $this->filteredCourses = [];
        $this->exam_date = now()->format('Y-m-d');
        $this->resetValidation();
    }

    public function save()
    {
        $this->validate();

        $hasAnyResult = false;
        foreach ($this->courseResults as $result) {
            if (($result['correct'] !== '' && $result['correct'] !== null) ||
                ($result['wrong'] !== '' && $result['wrong'] !== null) ||
                ($result['blank'] !== '' && $result['blank'] !== null)) {
                $hasAnyResult = true;
                break;
            }
        }

        if (!$hasAnyResult) {
            $this->addError('field_id', 'En az bir ders için doğru, yanlış veya boş değeri girmelisiniz.');
            return;
        }

        foreach ($this->courseResults as $courseId => $result) {
            if (($result['correct'] !== '' && $result['correct'] !== null) ||
                ($result['wrong'] !== '' && $result['wrong'] !== null) ||
                ($result['blank'] !== '' && $result['blank'] !== null)) {
                
                $correct = ($result['correct'] !== '' && $result['correct'] !== null) ? (int) $result['correct'] : 0;
                $wrong = ($result['wrong'] !== '' && $result['wrong'] !== null) ? (int) $result['wrong'] : 0;
                $blank = ($result['blank'] !== '' && $result['blank'] !== null) ? (int) $result['blank'] : 0;
                $net = $correct - ($wrong / 4);

                ExamResult::create([
                    'student_id' => auth()->id(),
                    'exam_name' => $this->exam_name,
                    'exam_type' => $this->exam_type,
                    'field_id' => $this->field_id,
                    'course_id' => $courseId,
                    'correct_answers' => $correct,
                    'wrong_answers' => $wrong,
                    'blank_answers' => $blank,
                    'net_score' => $net,
                    'exam_date' => $this->exam_date,
                    'notes' => $this->notes,
                ]);
            }
        }

        session()->flash('message', 'Deneme sonuçlarınız başarıyla kaydedildi.');
        $this->closeModal();
    }

    public function deleteExam($examName, $examDate)
    {
        $dateFormatted = is_string($examDate) ? \Carbon\Carbon::parse($examDate)->format('Y-m-d') : $examDate->format('Y-m-d');
        ExamResult::where('student_id', auth()->id())
            ->where('exam_name', $examName)
            ->whereDate('exam_date', $dateFormatted)
            ->delete();

        session()->flash('message', 'Deneme sınavı kaydı başarıyla silindi.');
        $this->expandedExamKey = null;
        $this->resetPage();
    }

    public function render()
    {
        $courses = Course::where('is_active', true)->orderBy('name')->get();
        
        // Grouped list for the main table
        $groupedExamsQuery = ExamResult::where('student_id', auth()->id())
            ->when($this->activeTab && $this->activeTab !== 'Tümü', fn($q) => $q->where('exam_type', $this->activeTab))
            ->select('exam_name', 'exam_date', 'exam_type', 'student_id')
            ->selectRaw('SUM(correct_answers) as total_correct, SUM(wrong_answers) as total_wrong, SUM(blank_answers) as total_blank, SUM(net_score) as total_net')
            ->groupBy('exam_name', 'exam_date', 'exam_type', 'student_id')
            ->orderBy('exam_date', 'desc');

        $examResults = $groupedExamsQuery->paginate(10);

        // Grouped stats for total average net, best, worst
        $statsQuery = ExamResult::where('student_id', auth()->id())
            ->when($this->activeTab && $this->activeTab !== 'Tümü', fn($q) => $q->where('exam_type', $this->activeTab))
            ->select('exam_name', 'exam_date', 'exam_type', 'student_id')
            ->selectRaw('SUM(net_score) as total_net')
            ->groupBy('exam_name', 'exam_date', 'exam_type', 'student_id')
            ->orderBy('exam_date', 'asc')
            ->get();

        $totalExams = $statsQuery->count();
        $stats = [
            'total_exams' => $totalExams,
            'avg_net' => $totalExams > 0 ? round($statsQuery->avg('total_net'), 2) : 0,
            'best_net' => $totalExams > 0 ? round($statsQuery->max('total_net'), 2) : 0,
            'worst_net' => $totalExams > 0 ? round($statsQuery->min('total_net'), 2) : 0,
        ];

        // Stats by field
        $fieldStats = [];
        $fieldsData = Field::courseFields()->where('is_active', true)->get();
        foreach ($fieldsData as $field) {
            $fieldStatsQuery = ExamResult::where('student_id', auth()->id())
                ->where('field_id', $field->id)
                ->when($this->activeTab && $this->activeTab !== 'Tümü', fn($q) => $q->where('exam_type', $this->activeTab))
                ->select('exam_name', 'exam_date', 'exam_type', 'student_id')
                ->selectRaw('SUM(net_score) as total_net')
                ->groupBy('exam_name', 'exam_date', 'exam_type', 'student_id')
                ->get();
            
            if ($fieldStatsQuery->count() > 0) {
                $fieldStats[$field->name] = [
                    'count' => $fieldStatsQuery->count(),
                    'avg_net' => round($fieldStatsQuery->avg('total_net'), 2),
                    'best_net' => round($fieldStatsQuery->max('total_net'), 2),
                ];
            }
        }

        // Unique Exams for Comparison Selector
        $uniqueExamsQuery = ExamResult::where('student_id', auth()->id());
        if ($this->activeTab && $this->activeTab !== 'Tümü') {
            $uniqueExamsQuery->where('exam_type', $this->activeTab);
        }
        $uniqueExams = $uniqueExamsQuery->select('exam_name', 'exam_date', 'exam_type')
            ->distinct()
            ->orderBy('exam_date', 'desc')
            ->orderBy('exam_name', 'asc')
            ->get();
        
        $uniqueExamsList = [];
        $examCounter = [];
        foreach ($uniqueExams as $item) {
            $typeKey = $item->exam_type ?: 'Genel';
            if (!isset($examCounter[$typeKey])) {
                $examCounter[$typeKey] = 0;
            }
            $examCounter[$typeKey]++;
            
            $examDate = is_string($item->exam_date) ? \Carbon\Carbon::parse($item->exam_date) : $item->exam_date;
            
            $totalNet = ExamResult::where('student_id', auth()->id())
                ->where('exam_name', $item->exam_name)
                ->whereDate('exam_date', $examDate->format('Y-m-d'))
                ->sum('net_score');
                
            $key = $examDate->format('Y-m-d') . '|' . $item->exam_name;
            $label = $item->exam_name . 
                      ($item->exam_type ? ' (' . $item->exam_type . ' #' . $examCounter[$typeKey] . ')' : '') . 
                      ' - ' . $examDate->format('d.m.Y') . 
                      ' - Toplam: ' . number_format($totalNet, 2) . ' Net';
                      
            $uniqueExamsList[] = [
                'key' => $key,
                'label' => $label,
                'exam_name' => $item->exam_name,
                'exam_date' => $examDate->format('Y-m-d'),
                'exam_type' => $item->exam_type,
                'total_net' => round($totalNet, 2),
            ];
        }

        // Dynamically filter second comparison exam to match first exam's type
        $uniqueExamsListSecond = $uniqueExamsList;
        if ($this->compareExamA) {
            $firstExamDetail = collect($uniqueExamsList)->firstWhere('key', $this->compareExamA);
            if ($firstExamDetail) {
                $firstExamType = $firstExamDetail['exam_type'];
                $uniqueExamsListSecond = collect($uniqueExamsList)
                    ->filter(function($exam) use ($firstExamType) {
                        return $exam['exam_type'] === $firstExamType && $exam['key'] !== $this->compareExamA;
                    })
                    ->values()
                    ->toArray();
            }
        }

        // Exam Comparison Logic
        $comparison = null;
        if ($this->compareExamA && $this->compareExamB) {
            $partsA = explode('|', $this->compareExamA);
            $partsB = explode('|', $this->compareExamB);
            
            if (count($partsA) == 2 && count($partsB) == 2) {
                $dateA = $partsA[0];
                $nameA = $partsA[1];
                $dateB = $partsB[0];
                $nameB = $partsB[1];
                
                $resultsA = ExamResult::where('student_id', auth()->id())
                    ->whereDate('exam_date', $dateA)
                    ->where('exam_name', $nameA)
                    ->with('course')
                    ->get();
                    
                $resultsB = ExamResult::where('student_id', auth()->id())
                    ->whereDate('exam_date', $dateB)
                    ->where('exam_name', $nameB)
                    ->with('course')
                    ->get();
                    
                $coursesData = [];
                $totalNetA = 0;
                $totalNetB = 0;
                
                foreach ($resultsA as $rA) {
                    $cId = $rA->course_id;
                    $cName = $rA->course?->name ?? 'Bilinmeyen';
                    $coursesData[$cId] = [
                        'name' => $cName,
                        'correct_A' => $rA->correct_answers,
                        'wrong_A' => $rA->wrong_answers,
                        'blank_A' => $rA->blank_answers,
                        'net_A' => $rA->net_score,
                        'correct_B' => 0,
                        'wrong_B' => 0,
                        'blank_B' => 0,
                        'net_B' => 0.00,
                        'net_diff' => -$rA->net_score,
                    ];
                    $totalNetA += $rA->net_score;
                }
                
                foreach ($resultsB as $rB) {
                    $cId = $rB->course_id;
                    $cName = $rB->course?->name ?? 'Bilinmeyen';
                    if (!isset($coursesData[$cId])) {
                        $coursesData[$cId] = [
                            'name' => $cName,
                            'correct_A' => 0,
                            'wrong_A' => 0,
                            'blank_A' => 0,
                            'net_A' => 0.00,
                            'correct_B' => $rB->correct_answers,
                            'wrong_B' => $rB->wrong_answers,
                            'blank_B' => $rB->blank_answers,
                            'net_B' => $rB->net_score,
                            'net_diff' => $rB->net_score,
                        ];
                    } else {
                        $coursesData[$cId]['correct_B'] = $rB->correct_answers;
                        $coursesData[$cId]['wrong_B'] = $rB->wrong_answers;
                        $coursesData[$cId]['blank_B'] = $rB->blank_answers;
                        $coursesData[$cId]['net_B'] = $rB->net_score;
                        $coursesData[$cId]['net_diff'] = $rB->net_score - $coursesData[$cId]['net_A'];
                    }
                    $totalNetB += $rB->net_score;
                }
                
                $comparison = [
                    'examA_name' => $nameA . ' (' . \Carbon\Carbon::parse($dateA)->format('d.m.Y') . ')',
                    'examB_name' => $nameB . ' (' . \Carbon\Carbon::parse($dateB)->format('d.m.Y') . ')',
                    'courses' => $coursesData,
                    'total_net_A' => $totalNetA,
                    'total_net_B' => $totalNetB,
                    'total_net_diff' => $totalNetB - $totalNetA,
                ];
            }
        }

        // Progress Chart Data (Last 8 exams chronologically)
        $chartDataGrouped = $statsQuery->map(function($exam) {
            $examDate = is_string($exam->exam_date) ? \Carbon\Carbon::parse($exam->exam_date) : $exam->exam_date;
            return [
                'date_name' => $exam->exam_name . ' (' . $examDate->format('d.m.Y') . ')',
                'date_raw' => $examDate->format('Y-m-d'),
                'total_net' => $exam->total_net,
            ];
        })->sortBy('date_raw')->take(8)->values();

        $chartLabels = $chartDataGrouped->pluck('date_name')->toArray();
        $chartNets = $chartDataGrouped->pluck('total_net')->toArray();

        return view('livewire.student.exam-logger', [
            'courses' => $courses,
            'examResults' => $examResults,
            'stats' => $stats,
            'fieldStats' => $fieldStats,
            'uniqueExamsList' => $uniqueExamsList,
            'uniqueExamsListSecond' => $uniqueExamsListSecond,
            'comparison' => $comparison,
            'chartLabels' => $chartLabels,
            'chartNets' => $chartNets,
        ]);
    }
}
