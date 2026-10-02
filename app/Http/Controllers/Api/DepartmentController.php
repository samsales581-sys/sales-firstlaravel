<?php
// Student: Sales, Samuel Jr. C.
// Section: BSIT 3-B



namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Department;

class DepartmentController extends Controller
{
    /**
     * Display all departments.
     */
    public function index()
    {
        $departments = Department::orderBy('name')->get();

        return response()->json([
            'message' => 'Departments retrieved successfully',
            'data' => $departments
        ], 200);
    }
}