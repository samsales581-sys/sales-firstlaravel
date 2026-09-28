<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    /**
     * Display employees.
     */
    public function index(Request $request)
    {
        // Load department relationship
        $query = Employee::with('department');

        // Search by employee number, first name, last name, or email
        if ($request->has('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Filter by department
        if ($request->has('department_id')) {
            $query->where(
                'department_id',
                $request->department_id
            );
        }

        // 10 employees per page
        return response()->json(
            $query->paginate(10)
        );
    }

    /**
     * Create employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_number' => 'required|string|max:50|unique:employees,employee_number',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'email' => 'required|email|unique:employees,email',
            'position' => 'required|string|max:100',
            'department_id' => 'required|exists:departments,id',
        ]);

        $employee = Employee::create($validated);

        return response()->json(
            $employee,
            201
        );
    }

    /**
     * Show one employee.
     */
    public function show($id)
    {
        $employee = Employee::with('department')->find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        return response()->json($employee);
    }

    /**
     * Update employee.
     */
    public function update(Request $request, $id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        $validated = $request->validate([
            'employee_number' => 'sometimes|string|max:50|unique:employees,employee_number,' . $id,
            'first_name' => 'sometimes|string|max:100',
            'last_name' => 'sometimes|string|max:100',
            'email' => 'sometimes|email|unique:employees,email,' . $id,
            'position' => 'sometimes|string|max:100',
            'department_id' => 'sometimes|exists:departments,id',
        ]);

        $employee->update($validated);

        return response()->json($employee);
    }

    /**
     * Delete employee.
     */
    public function destroy($id)
    {
        $employee = Employee::find($id);

        if (!$employee) {
            return response()->json([
                'message' => 'Employee not found'
            ], 404);
        }

        $employee->delete();

        return response()->json([
            'message' => 'Employee deleted successfully'
        ]);
    }
}