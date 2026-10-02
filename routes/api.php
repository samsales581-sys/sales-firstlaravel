<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Models\students;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\DepartmentController;


// =====================================================
// STUDENT ROUTES
// =====================================================

Route::get('/students', function () {
    return response()->json([
        [
            'id' => 1,
            'name' => 'Sales, Samuel Jr. C.',
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


// =====================================================
// PUBLIC AUTH ROUTES
// =====================================================

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


// =====================================================
// PUBLIC EXAM API ROUTES
// =====================================================

// Get all departments
Route::get('/departments', [DepartmentController::class, 'index']);

// Get paginated/searchable employees
Route::get('/employees', [EmployeeController::class, 'index']);

// Get one employee
Route::get('/employees/{id}', [EmployeeController::class, 'show']);


// =====================================================
// PROTECTED ROUTES - SANCTUM TOKEN REQUIRED
// =====================================================

Route::middleware('auth:sanctum')->group(function () {

    // Logout / revoke token
    Route::post('/logout', [AuthController::class, 'logout']);

    // Create employee
    Route::post('/employees', [EmployeeController::class, 'store']);

    // Update employee
    Route::put('/employees/{id}', [EmployeeController::class, 'update']);
    Route::patch('/employees/{id}', [EmployeeController::class, 'update']);

    // Delete employee
    Route::delete('/employees/{id}', [EmployeeController::class, 'destroy']);

});