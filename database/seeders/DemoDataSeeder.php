<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\ExamResult;
use App\Models\Field;
use App\Models\QuestionLog;
use App\Models\Role;
use App\Models\StudyLog;
use App\Models\SubTopic;
use App\Models\Topic;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studentRole = Role::where('name', 'student')->first();
        $students = User::where('role_id', $studentRole->id)->get();

        if ($students->isEmpty()) {
            return;
        }

        // Her öğrenci için örnek veriler
        foreach ($students as $student) {
            $this->createQuestionLogs($student);
            $this->createExamResults($student);
            $this->createStudyLogs($student);
        }
    }

    private function createQuestionLogs($student)
    {
        // TYT ve AYT derslerini al
        $tytField = Field::where('slug', 'tyt')->first();
        $aytField = Field::where('slug', 'ayt')->first();
        
        $tytCourses = Course::where('field_id', $tytField?->id)
            ->where('is_active', true)
            ->with(['topics.subTopics'])
            ->take(5)
            ->get();
            
        $aytCourses = Course::where('field_id', $aytField?->id)
            ->where('is_active', true)
            ->with(['topics.subTopics'])
            ->take(5)
            ->get();
        
        $allCourses = $tytCourses->merge($aytCourses);
        
        // Her öğrenci için 40-50 soru kaydı oluştur (son 90 gün içinde)
        $totalLogs = rand(40, 50);
        $baseSuccessRate = rand(45, 60); // Başlangıç başarı oranı
        
        // Her ders için konu bazlı kayıtlar oluştur
        foreach ($allCourses as $course) {
            if ($course->topics->isEmpty()) continue;
            
            $topics = $course->topics->take(3); // Her ders için 3 konu
            
            foreach ($topics as $topic) {
                $subTopics = $topic->subTopics->take(2); // Her konu için 2 alt konu
                
                // Her alt konu için 2-3 kayıt oluştur
                $logsPerSubTopic = rand(2, 3);
                
                for ($i = 0; $i < $logsPerSubTopic; $i++) {
                    // Zaman içinde gelişim göster (başlangıçtan daha iyi sonuçlar)
                    $daysAgo = rand(0, 90);
                    $progressFactor = 1 + ((90 - $daysAgo) / 90 * 0.3); // %30'a kadar iyileşme
                    
                    // Başarı oranını hesapla (zaman içinde artış göster)
                    $currentSuccessRate = min(95, $baseSuccessRate * $progressFactor + rand(-5, 10));
                    
                    $totalQuestions = rand(30, 100);
                    $correctAnswers = (int)($totalQuestions * ($currentSuccessRate / 100));
                    $wrongAnswers = rand(5, (int)(($totalQuestions - $correctAnswers) * 0.7));
                    $blankAnswers = max(0, $totalQuestions - $correctAnswers - $wrongAnswers);
                    
                    // Doğru cevap sayısını düzelt
                    if ($correctAnswers + $wrongAnswers + $blankAnswers != $totalQuestions) {
                        $correctAnswers = $totalQuestions - $wrongAnswers - $blankAnswers;
                    }
                    
                    QuestionLog::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'topic_id' => $topic->id,
                        'sub_topic_id' => $subTopics->isNotEmpty() ? $subTopics->random()->id : null,
                        'total_questions' => $totalQuestions,
                        'correct_answers' => max(0, $correctAnswers),
                        'wrong_answers' => max(0, $wrongAnswers),
                        'blank_answers' => max(0, $blankAnswers),
                        'log_date' => Carbon::now()->subDays($daysAgo),
                        'notes' => rand(0, 3) === 1 ? 'Güzel bir çalışma oldu.' : (rand(0, 3) === 1 ? 'Daha fazla pratik gerekiyor.' : null),
                    ]);
                }
                
                // Alt konu olmayan konular için de kayıt ekle
                if ($subTopics->isEmpty()) {
                    $daysAgo = rand(0, 90);
                    $progressFactor = 1 + ((90 - $daysAgo) / 90 * 0.3);
                    $currentSuccessRate = min(95, $baseSuccessRate * $progressFactor + rand(-5, 10));
                    
                    $totalQuestions = rand(30, 100);
                    $correctAnswers = (int)($totalQuestions * ($currentSuccessRate / 100));
                    $wrongAnswers = rand(5, (int)(($totalQuestions - $correctAnswers) * 0.7));
                    $blankAnswers = max(0, $totalQuestions - $correctAnswers - $wrongAnswers);
                    
                    if ($correctAnswers + $wrongAnswers + $blankAnswers != $totalQuestions) {
                        $correctAnswers = $totalQuestions - $wrongAnswers - $blankAnswers;
                    }
                    
                    QuestionLog::create([
                        'student_id' => $student->id,
                        'course_id' => $course->id,
                        'topic_id' => $topic->id,
                        'sub_topic_id' => null,
                        'total_questions' => $totalQuestions,
                        'correct_answers' => max(0, $correctAnswers),
                        'wrong_answers' => max(0, $wrongAnswers),
                        'blank_answers' => max(0, $blankAnswers),
                        'log_date' => Carbon::now()->subDays($daysAgo),
                        'notes' => rand(0, 2) === 1 ? 'Konu tekrarı yapıldı.' : null,
                    ]);
                }
            }
        }
        
        // Ek rastgele kayıtlar (konu bilgisi olmadan)
        $remainingLogs = $totalLogs - QuestionLog::where('student_id', $student->id)->count();
        if ($remainingLogs > 0) {
            for ($i = 0; $i < $remainingLogs; $i++) {
                $daysAgo = rand(0, 90);
                $progressFactor = 1 + ((90 - $daysAgo) / 90 * 0.2);
                $currentSuccessRate = min(95, $baseSuccessRate * $progressFactor + rand(-5, 10));
                
                $totalQuestions = rand(50, 150);
                $correctAnswers = (int)($totalQuestions * ($currentSuccessRate / 100));
                $wrongAnswers = rand(5, (int)(($totalQuestions - $correctAnswers) * 0.6));
                $blankAnswers = max(0, $totalQuestions - $correctAnswers - $wrongAnswers);
                
                if ($correctAnswers + $wrongAnswers + $blankAnswers != $totalQuestions) {
                    $correctAnswers = $totalQuestions - $wrongAnswers - $blankAnswers;
                }
                
                QuestionLog::create([
                    'student_id' => $student->id,
                    'course_id' => $allCourses->random()->id ?? null,
                    'topic_id' => null,
                    'sub_topic_id' => null,
                    'total_questions' => $totalQuestions,
                    'correct_answers' => max(0, $correctAnswers),
                    'wrong_answers' => max(0, $wrongAnswers),
                    'blank_answers' => max(0, $blankAnswers),
                    'log_date' => Carbon::now()->subDays($daysAgo),
                    'notes' => rand(0, 2) === 1 ? 'Genel soru çözümü.' : null,
                ]);
            }
        }
    }

    private function createExamResults($student)
    {
        $courses = Course::where('is_active', true)->get();
        $fields = Field::examCategories()->get();
        $tytField = Field::where('slug', 'tyt')->first();
        $aytField = Field::where('slug', 'ayt')->first();
        
        // Deneme türleri ve isimleri
        $examTypes = ['TYT', 'AYT', 'TYT', 'AYT', 'TYT', 'AYT', 'TYT', 'AYT', 'TYT', 'AYT', 'TYT', 'AYT', 'TYT', 'AYT', 'TYT'];
        $examNames = [
            'TYT Deneme 1', 'TYT Deneme 2', 'TYT Deneme 3', 'TYT Deneme 4', 'TYT Deneme 5',
            'TYT Deneme 6', 'TYT Deneme 7', 'TYT Deneme 8',
            'AYT Matematik Deneme 1', 'AYT Matematik Deneme 2', 'AYT Fen Deneme 1', 'AYT Fen Deneme 2',
            'AYT Edebiyat Deneme 1', 'AYT Edebiyat Deneme 2', 'AYT Genel Deneme'
        ];

        // Her öğrenci için 20-25 deneme sonucu oluştur (son 3 ay içinde)
        $baseNet = rand(20, 30); // Başlangıç net skoru
        $examCount = rand(20, 25);
        
        for ($i = 0; $i < $examCount; $i++) {
            $examType = $examTypes[$i % count($examTypes)];
            $examName = $examNames[$i % count($examNames)];
            
            // TYT için daha yüksek soru sayıları
            if ($examType === 'TYT') {
                $correctAnswers = rand(25, 50);
                $wrongAnswers = rand(5, 20);
                $blankAnswers = rand(0, 15);
            } else {
                // AYT için daha düşük soru sayıları
                $correctAnswers = rand(15, 35);
                $wrongAnswers = rand(3, 15);
                $blankAnswers = rand(0, 10);
            }
            
            // Net skor hesaplama (4 yanlış = 1 doğru düşer)
            $netScore = $correctAnswers - ($wrongAnswers / 4);
            
            // Zaman içinde gelişim göster (başlangıçtan daha iyi sonuçlar)
            $progressFactor = 1 + ($i * 0.02); // Her denemede %2 iyileşme
            $netScore = round($netScore * $progressFactor, 2);
            
            // Rastgele dalgalanma ekle
            $netScore += rand(-3, 5);
            $netScore = max(0, $netScore); // Negatif olamaz
            
            // Field belirleme
            $fieldId = null;
            if ($examType === 'TYT' && $tytField) {
                $fieldId = $tytField->id;
            } elseif ($examType === 'AYT' && $aytField) {
                $fieldId = $aytField->id;
            } else {
                $fieldId = $fields->random()->id ?? null;
            }
            
            // Tarih: Son 90 gün içinde, haftada 1-2 deneme
            $daysAgo = ($examCount - $i) * rand(3, 5); // Her deneme 3-5 gün arayla
            
            ExamResult::create([
                'student_id' => $student->id,
                'exam_name' => $examName,
                'exam_type' => $examType,
                'course_id' => $courses->random()->id ?? null,
                'field_id' => $fieldId,
                'correct_answers' => $correctAnswers,
                'wrong_answers' => $wrongAnswers,
                'blank_answers' => $blankAnswers,
                'net_score' => round($netScore, 2),
                'exam_date' => Carbon::now()->subDays($daysAgo),
                'notes' => rand(0, 2) === 1 ? 'İyi bir deneme oldu.' : (rand(0, 2) === 1 ? 'Daha iyi olabilirdi.' : null),
            ]);
        }
    }

    private function createStudyLogs($student)
    {
        $topics = Topic::where('is_active', true)->inRandomOrder()->take(10)->get();
        $subTopics = SubTopic::where('is_active', true)->inRandomOrder()->take(10)->get();
        
        $resourceTypes = ['video', 'book', 'notes', 'other'];
        $videoTitles = [
            'Khan Academy Türev Dersi',
            'YouTube: Matematik Temelleri',
            'Udemy: Fizik Konu Anlatımı',
            'Online Ders: Kimya',
            'TYT Matematik Konu Anlatımı',
        ];

        // 20 çalışma kaydı
        for ($i = 0; $i < 20; $i++) {
            $resourceType = $resourceTypes[array_rand($resourceTypes)];
            
            StudyLog::create([
                'student_id' => $student->id,
                'topic_id' => $topics->random()->id ?? null,
                'sub_topic_id' => rand(0, 1) ? ($subTopics->random()->id ?? null) : null,
                'resource_type' => $resourceType,
                'resource_title' => $resourceType === 'video' ? $videoTitles[array_rand($videoTitles)] : null,
                'description' => 'Konuyu detaylı şekilde çalıştım. ' . (rand(0, 1) ? 'Örnekleri çözdüm.' : 'Notlar çıkardım.'),
                'study_date' => Carbon::now()->subDays(rand(0, 45)),
            ]);
        }
    }
}
