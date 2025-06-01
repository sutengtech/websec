<?php

require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

echo "Test Users Verification:\n";
echo "========================\n\n";

$users = [
    'student@example.com' => 'STUDENT',
    'teacher@example.com' => 'TEACHER', 
    'manager@example.com' => 'MANAGER'
];

foreach($users as $email => $type) {
    $user = User::where('email', $email)->first();
    if($user) {
        echo "$type ($email):\n";
        echo "  Roles: " . $user->getRoleNames()->implode(', ') . "\n";
        echo "  Permissions: " . $user->getAllPermissions()->pluck('name')->implode(', ') . "\n\n";
    } else {
        echo "$type ($email): NOT FOUND\n\n";
    }
}
