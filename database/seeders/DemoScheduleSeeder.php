<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Role;
use App\Models\ScheduleItem;
use App\Models\StudySchedule;
use App\Models\SubTopic;
use App\Models\Topic;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $coachRole = Role::where('name', 'coach')->first();
        $studentRole = Role::where('name', 'student')->first();

        if (!$coachRole || !$studentRole) {
            return;
        }

        // İlk koç ve öğrenciyi bul
        $coach = User::where('role_id', $coachRole->id)->first();
        $student = User::where('role_id', $studentRole->id)->first();

        if (!$coach || !$student) {
            return;
        }

        // Örnek program oluştur
        $schedule = StudySchedule::create([
            'coach_id' => $coach->id,
            'student_id' => $student->id,
            'name' => $student->name . ' için Aralık Programı',
            'is_active' => true,
        ]);

        // Dersler ve konular al
        $mathCourse = Course::where('name', 'LIKE', '%Matematik%')->first();
        $physicsCourse = Course::where('name', 'LIKE', '%Fizik%')->first();
        $turkishCourse = Course::where('name', 'LIKE', '%Türkçe%')->first();

        $tasks = [];

        // Pazartesi
        if ($mathCourse) {
            $mathTopic = Topic::where('course_id', $mathCourse->id)->first();
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 1,
                'time_slot' => '09:00-11:00',
                'course_id' => $mathCourse->id,
                'topic_id' => $mathTopic?->id,
                'sub_topic_id' => null,
                'question_count' => 50,
                'description' => 'Konu tekrarı ve soru çözümü',
                'order' => 1,
            ];
        }

        if ($physicsCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 1,
                'time_slot' => '14:00-16:00',
                'course_id' => $physicsCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 30,
                'description' => 'Video izle ve not çıkar',
                'order' => 2,
            ];
        }

        // Salı
        if ($turkishCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 2,
                'time_slot' => '10:00-12:00',
                'course_id' => $turkishCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 40,
                'description' => 'Paragraf ve anlam bilgisi',
                'order' => 1,
            ];
        }

        if ($mathCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 2,
                'time_slot' => '15:00-17:00',
                'course_id' => $mathCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 40,
                'description' => 'Test çöz',
                'order' => 2,
            ];
        }

        // Çarşamba
        if ($physicsCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 3,
                'time_slot' => '09:00-11:00',
                'course_id' => $physicsCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 35,
                'description' => 'Kuvvet ve hareket konusu',
                'order' => 1,
            ];
        }

        // Perşembe
        if ($mathCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 4,
                'time_slot' => '10:00-12:00',
                'course_id' => $mathCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 50,
                'description' => 'Deneme çöz',
                'order' => 1,
            ];
        }

        if ($turkishCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 4,
                'time_slot' => '14:00-16:00',
                'course_id' => $turkishCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 30,
                'description' => 'Edebiyat tarihi',
                'order' => 2,
            ];
        }

        // Cuma
        if ($mathCourse && $physicsCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 5,
                'time_slot' => '09:00-12:00',
                'course_id' => $mathCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 60,
                'description' => 'Haftalık genel tekrar',
                'order' => 1,
            ];
        }

        // Cumartesi - Deneme günü
        $tasks[] = [
            'schedule_id' => $schedule->id,
            'day_of_week' => 6,
            'time_slot' => '10:00-13:00',
            'course_id' => null,
            'topic_id' => null,
            'sub_topic_id' => null,
            'question_count' => 0,
            'description' => 'TYT Deneme Sınavı',
            'order' => 1,
        ];

        // Pazar - Dinlenme/Hafif çalışma
        if ($turkishCourse) {
            $tasks[] = [
                'schedule_id' => $schedule->id,
                'day_of_week' => 7,
                'time_slot' => '14:00-16:00',
                'course_id' => $turkishCourse->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => 20,
                'description' => 'Hafif tempo - Kitap oku',
                'order' => 1,
            ];
        }

        foreach ($tasks as $task) {
            ScheduleItem::create($task);
        }
        
        // Örnek Şablonlar Oluştur
        $this->createTemplates($coach, $mathCourse, $physicsCourse, $turkishCourse);
    }
    
    private function createTemplates($coach, $mathCourse, $physicsCourse, $turkishCourse)
    {
        // Şablon 1: Sayısal Öğrenci Programı (Saatli)
        $template1 = StudySchedule::create([
            'coach_id' => $coach->id,
            'student_id' => null,
            'name' => 'Sayısal Öğrenci Şablonu (Saatli)',
            'is_active' => true,
            'is_template' => true,
            'schedule_type' => 'timed',
        ]);
        
        $template1Tasks = [
            // Pazartesi
            ['day' => 1, 'time' => '09:00-11:00', 'course' => $mathCourse, 'questions' => 50, 'desc' => 'Matematik konu anlatımı ve örnekler'],
            ['day' => 1, 'time' => '14:00-16:00', 'course' => $physicsCourse, 'questions' => 40, 'desc' => 'Fizik problemleri'],
            // Salı
            ['day' => 2, 'time' => '09:00-11:00', 'course' => $mathCourse, 'questions' => 60, 'desc' => 'Test çözümü'],
            ['day' => 2, 'time' => '15:00-17:00', 'course' => $physicsCourse, 'questions' => 35, 'desc' => 'Video izle ve not al'],
            // Çarşamba
            ['day' => 3, 'time' => '10:00-12:00', 'course' => $mathCourse, 'questions' => 55, 'desc' => 'Deneme çöz'],
            ['day' => 3, 'time' => '14:00-16:00', 'course' => $physicsCourse, 'questions' => 40, 'desc' => 'Konu tekrarı'],
            // Perşembe
            ['day' => 4, 'time' => '09:00-11:00', 'course' => $mathCourse, 'questions' => 50, 'desc' => 'Eksik konular'],
            ['day' => 4, 'time' => '15:00-17:00', 'course' => $physicsCourse, 'questions' => 30, 'desc' => 'Problem çözümü'],
            // Cuma
            ['day' => 5, 'time' => '09:00-12:00', 'course' => $mathCourse, 'questions' => 70, 'desc' => 'Haftalık genel tekrar'],
            // Cumartesi
            ['day' => 6, 'time' => '10:00-13:00', 'course' => null, 'questions' => 0, 'desc' => 'AYT Deneme Sınavı'],
        ];
        
        foreach ($template1Tasks as $task) {
            ScheduleItem::create([
                'schedule_id' => $template1->id,
                'day_of_week' => $task['day'],
                'time_slot' => $task['time'],
                'course_id' => $task['course']?->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => $task['questions'],
                'description' => $task['desc'],
                'order' => 0,
            ]);
        }
        
        // Şablon 2: Sözel Öğrenci Programı (Saatli)
        $template2 = StudySchedule::create([
            'coach_id' => $coach->id,
            'student_id' => null,
            'name' => 'Sözel Öğrenci Şablonu (Saatli)',
            'is_active' => true,
            'is_template' => true,
            'schedule_type' => 'timed',
        ]);
        
        $template2Tasks = [
            // Pazartesi
            ['day' => 1, 'time' => '09:00-11:00', 'course' => $turkishCourse, 'questions' => 40, 'desc' => 'Türkçe paragraf çalışması'],
            ['day' => 1, 'time' => '14:00-16:00', 'course' => $turkishCourse, 'questions' => 30, 'desc' => 'Edebiyat tarihi'],
            // Salı
            ['day' => 2, 'time' => '10:00-12:00', 'course' => $turkishCourse, 'questions' => 50, 'desc' => 'Test çöz'],
            ['day' => 2, 'time' => '15:00-17:00', 'course' => $turkishCourse, 'questions' => 35, 'desc' => 'Anlam bilgisi'],
            // Çarşamba
            ['day' => 3, 'time' => '09:00-11:00', 'course' => $turkishCourse, 'questions' => 45, 'desc' => 'Kompozisyon çalışması'],
            ['day' => 3, 'time' => '14:00-16:00', 'course' => $turkishCourse, 'questions' => 40, 'desc' => 'Deneme çöz'],
            // Perşembe
            ['day' => 4, 'time' => '10:00-12:00', 'course' => $turkishCourse, 'questions' => 50, 'desc' => 'Konu anlatımı'],
            ['day' => 4, 'time' => '15:00-17:00', 'course' => $turkishCourse, 'questions' => 30, 'desc' => 'Kitap oku'],
            // Cuma
            ['day' => 5, 'time' => '09:00-12:00', 'course' => $turkishCourse, 'questions' => 60, 'desc' => 'Haftalık tekrar'],
            // Cumartesi
            ['day' => 6, 'time' => '10:00-13:00', 'course' => null, 'questions' => 0, 'desc' => 'TYT Deneme Sınavı'],
        ];
        
        foreach ($template2Tasks as $task) {
            ScheduleItem::create([
                'schedule_id' => $template2->id,
                'day_of_week' => $task['day'],
                'time_slot' => $task['time'],
                'course_id' => $task['course']?->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => $task['questions'],
                'description' => $task['desc'],
                'order' => 0,
            ]);
        }
        
        // Şablon 3: Saatsiz Günlük Program
        $template3 = StudySchedule::create([
            'coach_id' => $coach->id,
            'student_id' => null,
            'name' => 'Günlük Görevler Şablonu (Saatsiz)',
            'is_active' => true,
            'is_template' => true,
            'schedule_type' => 'daily',
        ]);
        
        $template3Tasks = [
            // Pazartesi
            ['day' => 1, 'course' => $mathCourse, 'questions' => 40, 'desc' => 'Matematik konu anlatımı izle ve not al'],
            ['day' => 1, 'course' => $physicsCourse, 'questions' => 30, 'desc' => 'Fizik test çöz'],
            // Salı
            ['day' => 2, 'course' => $mathCourse, 'questions' => 50, 'desc' => 'Matematik problemleri çöz'],
            ['day' => 2, 'course' => $turkishCourse, 'questions' => 25, 'desc' => 'Paragraf çalış'],
            // Çarşamba
            ['day' => 3, 'course' => $physicsCourse, 'questions' => 40, 'desc' => 'Fizik deneme sınavı çöz'],
            ['day' => 3, 'course' => $mathCourse, 'questions' => 35, 'desc' => 'Matematik tekrar'],
            // Perşembe
            ['day' => 4, 'course' => $turkishCourse, 'questions' => 30, 'desc' => 'Edebiyat konuları çalış'],
            ['day' => 4, 'course' => $mathCourse, 'questions' => 45, 'desc' => 'Eksik konuları tamamla'],
            // Cuma
            ['day' => 5, 'course' => null, 'questions' => 0, 'desc' => 'Haftalık genel tekrar ve eksikleri gider'],
            // Cumartesi
            ['day' => 6, 'course' => null, 'questions' => 0, 'desc' => 'Deneme sınavı çöz (TYT veya AYT)'],
            // Pazar
            ['day' => 7, 'course' => null, 'questions' => 0, 'desc' => 'Dinlenme ve motivasyon günü - hafif tekrar'],
        ];
        
        foreach ($template3Tasks as $task) {
            ScheduleItem::create([
                'schedule_id' => $template3->id,
                'day_of_week' => $task['day'],
                'time_slot' => null, // Saatsiz program
                'course_id' => $task['course']?->id,
                'topic_id' => null,
                'sub_topic_id' => null,
                'question_count' => $task['questions'],
                'description' => $task['desc'],
                'order' => 0,
            ]);
        }
    }
}


