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

        // Stats calculation
        $stats = [
            'total_entries' => $examResults->count(),
            'avg_net' => round($examResults->avg('net_score'), 2),
            'best_net' => round($examResults->max('net_score'), 2),
            'worst_net' => round($examResults->min('net_score'), 2),
            'total_exams' => $examResults->groupBy(function($item) {
                return $item->exam_date->format('Y-m-d') . '_' . $item->exam_name;
            })->count(),
        ];

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

        // Stats by field
        $fieldStats = [];
        $fieldsData = Field::courseFields()->where('is_active', true)->get();
        foreach ($fieldsData as $field) {
            $fieldResults = $examResults->where('field_id', $field->id);
            if ($fieldResults->count() > 0) {
                $fieldStats[$field->name] = [
                    'count' => $fieldResults->groupBy(function($item) {
                        return $item->exam_date->format('Y-m-d') . '_' . $item->exam_name;
                    })->count(),
                    'avg_net' => round($fieldResults->avg('net_score'), 2),
                    'best_net' => round($fieldResults->max('net_score'), 2),
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
