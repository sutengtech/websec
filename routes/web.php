<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome'); //welcome.blade.php
});

// Route::get('/multable', function () {
//     return view('multable', ['j' => 5]); // j is variable for mult table (multable.blade.php)
// });

Route::get('/even', function () {
    return view('even');  //even.blade.php
});

Route::get('/prime', function () {
    return view('prime'); //prime.blade.php
});


Route::get('/multable/{number?}', function ($number = null) {
    return view('multable', ['j' => $number ?? 2]); // commented the first one because it already gets the default value from here (multable.blade.php)
});

Route::get('/factorial/{number?}', function ($number = null) {
    return view('factorial', ['number' => $number ?? 5]); //factorial.blade.php (merged both mul)
});