<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\students;
use App\Http\Controllers\Api\EmployeeController;


Route::get('/students', function () {
    return response()->json([
        [
            'id' => 1,
            'name' => 'Sales, Samuel Jr. C.',
            'course' => 'BSIT 3B'
        ],
        [
            'id' => 2,
            'name' => 'Samuel Sales',
            'course' => 'BSIT 3B'
        ]
    ]);
});


Route::post('/students', function (Request $request) {
    $student = students::create([
        'name' => $request->name,
        'course' => $request->course,
    ]);

    return response()->json($student, 201);
});


Route::get('/employees', [EmployeeController::class, 'index']);

Route::post('/employees', [EmployeeController::class, 'store']);

Route::get('/employees/{id}', [EmployeeController::class, 'show']);

Route::put('/employees/{id}', [EmployeeController::class, 'update']);

Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);