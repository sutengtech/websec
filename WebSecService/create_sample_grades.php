<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Course;
use App\Models\User;
use App\Models\Grade;

echo "Checking existing data and creating sample grades...\n";

// Check courses
$courses = Course::all();
echo "Courses available: " . $courses->count() . "\n";
foreach($courses as $course) {
    echo "  - " . $course->name . " (ID: " . $course->id . ")\n";
}

// Check users
$users = User::all();
echo "Users available: " . $users->count() . "\n";

// Get student user
$student = User::where('email', 'student@example.com')->first();
if($student && $courses->count() > 0) {
    echo "Creating sample grades for student...\n";
    
    // Create sample grades for the student
    foreach($courses->take(3) as $course) {
        $existingGrade = Grade::where('user_id', $student->id)
                              ->where('course_id', $course->id)
                              ->first();
        
        if(!$existingGrade) {
            $grade = new Grade();
            $grade->user_id = $student->id;
            $grade->course_id = $course->id;
            $grade->degree = rand(60, 95);
            $grade->save();
            echo "  Created grade: " . $course->name . " - " . $grade->degree . "/100\n";
        } else {
            echo "  Grade already exists: " . $course->name . " - " . $existingGrade->degree . "/100\n";
        }
    }
} else {
    echo "Cannot create sample grades - student user or courses not found\n";
}

echo "Done!\n";
