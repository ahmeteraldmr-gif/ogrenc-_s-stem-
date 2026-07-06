<?php

namespace App\Http\Controllers;

use App\Models\ExamResult;
use App\Models\User;
use App\Models\Field;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ExamReportController extends Controller
{
    public function downloadStudentReport($studentId)
    {
        // Verify that the student belongs to the logged-in coach
        $student = auth()->user()->students()->findOrFail($studentId);
        return $this->generatePdf($student);
    }

    public function downloadMyReport()
    {
        $student = auth()->user();
        return $this->generatePdf($student);
    }

    protected function generatePdf(User $student)
    {
        $examResults = ExamResult::where('student_id', $student->id)
            ->with(['course', 'field'])
            ->orderBy('exam_date', 'desc')
            ->orderBy('exam_name')
            ->get();

        // If no results, redirect back with error message
        if ($examResults->isEmpty()) {
            return back()->with('error', 'Bu öğrencinin henüz kaydedilmiş deneme sınavı sonucu bulunmamaktadır.');
        }

        // Group results by exam date + name + type
        $groupedResults = [];
        foreach ($examResults as $result) {
            $key = $result->exam_date->format('Y-m-d') . '_' . $result->exam_name . '_' . $result->exam_type;
            if (!isset($groupedResults[$key])) {
                $groupedResults[$key] = [
                    'exam_date' => $result->exam_date,
                    'exam_name' => $result->exam_name,
                    'exam_type' => $result->exam_type,
                    'field_name' => $result->field?->name ?? '-',
                    'courses' => [],
                    'total_correct' => 0,
                    'total_wrong' => 0,
                    'total_blank' => 0,
                    'total_net' => 0.00,
                ];
            }
            $groupedResults[$key]['courses'][] = [
                'course_name' => $result->course?->name ?? '-',
                'correct' => $result->correct_answers,
                'wrong' => $result->wrong_answers,
                'blank' => $result->blank_answers,
                'net' => $result->net_score,
            ];
            $groupedResults[$key]['total_correct'] += $result->correct_answers;
            $groupedResults[$key]['total_wrong'] += $result->wrong_answers;
            $groupedResults[$key]['total_blank'] += $result->blank_answers;
            $groupedResults[$key]['total_net'] += $result->net_score;
        }

        // Stats calculation based on grouped overall exams
        $totalExams = count($groupedResults);
        $allTotalNets = collect($groupedResults)->pluck('total_net');
        
        $stats = [
            'total_entries' => $examResults->count(),
            'avg_net' => $totalExams > 0 ? round($allTotalNets->avg(), 2) : 0,
            'best_net' => $totalExams > 0 ? round($allTotalNets->max(), 2) : 0,
            'worst_net' => $totalExams > 0 ? round($allTotalNets->min(), 2) : 0,
            'total_exams' => $totalExams,
        ];

        // Stats by field (correctly calculating overall net score per exam)
        $fieldStats = [];
        $fieldsData = Field::courseFields()->where('is_active', true)->get();
        foreach ($fieldsData as $field) {
            $fieldExamGroups = [];
            foreach ($examResults->where('field_id', $field->id) as $res) {
                $fKey = $res->exam_date->format('Y-m-d') . '_' . $res->exam_name;
                if (!isset($fieldExamGroups[$fKey])) {
                    $fieldExamGroups[$fKey] = 0;
                }
                $fieldExamGroups[$fKey] += $res->net_score;
            }
            
            $fCount = count($fieldExamGroups);
            if ($fCount > 0) {
                $fNets = collect($fieldExamGroups);
                $fieldStats[$field->name] = [
                    'count' => $fCount,
                    'avg_net' => round($fNets->avg(), 2),
                    'best_net' => round($fNets->max(), 2),
                ];
            }
        }

        $coach = $student->coaches()->first();

        $data = [
            'student' => $student,
            'coach' => $coach,
            'stats' => $stats,
            'fieldStats' => $fieldStats,
            'groupedResults' => $groupedResults,
            'date' => Carbon::now()->format('d.m.Y H:i'),
        ];

        $pdf = Pdf::loadView('reports.exam-results-pdf', $data)
            ->setPaper('a4', 'portrait');

        $fileName = 'deneme-raporu-' . str($student->name)->slug() . '.pdf';
        return $pdf->download($fileName);
    }
}
