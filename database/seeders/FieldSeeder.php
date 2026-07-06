<?php

namespace Database\Seeders;

use App\Models\Field;
use Illuminate\Database\Seeder;

class FieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fields = [
            // Course fields (existing)
            ['name' => 'TYT', 'slug' => 'tyt', 'order' => 1, 'category_type' => 'course_field'],
            ['name' => 'AYT', 'slug' => 'ayt', 'order' => 2, 'category_type' => 'course_field'],
            ['name' => 'DGS', 'slug' => 'dgs', 'order' => 3, 'category_type' => 'course_field'],
            ['name' => 'KPSS', 'slug' => 'kpss', 'order' => 4, 'category_type' => 'course_field'],
            
            // Exam categories (new)
            ['name' => 'Sayısal', 'slug' => 'sayisal', 'order' => 10, 'category_type' => 'exam_category'],
            ['name' => 'Sosyal', 'slug' => 'sosyal', 'order' => 11, 'category_type' => 'exam_category'],
            ['name' => 'Eşit Ağırlık', 'slug' => 'esit-agirlik', 'order' => 12, 'category_type' => 'exam_category'],
            ['name' => 'Dil', 'slug' => 'dil', 'order' => 13, 'category_type' => 'exam_category'],
        ];

        foreach ($fields as $field) {
            Field::updateOrCreate(
                ['slug' => $field['slug']],
                $field
            );
        }
    }
}
