<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\User;
use App\Models\Grade;

class SampleDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample courses if they don't exist
        $courses = [
            ['name' => 'Mathematics', 'max_degree' => 100],
            ['name' => 'Physics', 'max_degree' => 100],
            ['name' => 'Chemistry', 'max_degree' => 100],
            ['name' => 'English Literature', 'max_degree' => 100],
            ['name' => 'Computer Science', 'max_degree' => 100],
            ['name' => 'History', 'max_degree' => 100],
        ];

        foreach ($courses as $courseData) {
            Course::firstOrCreate(
                ['name' => $courseData['name']],
                ['max_degree' => $courseData['max_degree']]
            );
        }

        $this->command->info('Sample courses created successfully.');

        // Create sample grades for the student user
        $student = User::where('email', 'student@example.com')->first();
        $allCourses = Course::all();

        if ($student && $allCourses->count() > 0) {
            // Create grades for the first 4 courses
            foreach ($allCourses->take(4) as $course) {
                Grade::firstOrCreate([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                ], [
                    'degree' => rand(60, 95),
                    'freezed' => 0,
                ]);
            }

            $this->command->info('Sample grades created for student user.');
        }

        // Create a few more sample students with grades for better testing
        $sampleStudents = [
            ['name' => 'Alice Johnson', 'email' => 'alice@example.com'],
            ['name' => 'Bob Smith', 'email' => 'bob@example.com'],
            ['name' => 'Carol Davis', 'email' => 'carol@example.com'],
        ];

        foreach ($sampleStudents as $studentData) {
            $sampleStudent = User::firstOrCreate(
                ['email' => $studentData['email']],
                [
                    'name' => $studentData['name'],
                    'password' => bcrypt('password123'),
                ]
            );

            // Assign exstudent role
            $exstudentRole = \Spatie\Permission\Models\Role::where('name', 'exstudent')->first();
            if ($exstudentRole && !$sampleStudent->hasRole('exstudent')) {
                $sampleStudent->assignRole($exstudentRole);
            }

            // Create 2-3 grades for each sample student
            foreach ($allCourses->take(rand(2, 3)) as $course) {
                Grade::firstOrCreate([
                    'user_id' => $sampleStudent->id,
                    'course_id' => $course->id,
                ], [
                    'degree' => rand(50, 100),
                    'freezed' => rand(0, 1),
                ]);
            }
        }

        $this->command->info('Additional sample students and grades created successfully.');
    }
}
