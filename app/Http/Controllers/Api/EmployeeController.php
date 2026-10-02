<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EmployeeResource;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EmployeeController extends Controller
{
    /**
     * Display employees with:
     * - pagination
     * - search
     * - department name search
     * - department filter
     * - employment status filter
     */
    public function index(Request $request)
    {
        // Validate query parameters
        $request->validate([
            'employment_status' => 'nullable|in:Active,Inactive',
            'department_id' => 'nullable|exists:departments,id',
        ]);

        // Load department relationship
        $query = Employee::with('department');

        // Search employees
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('employee_number', 'like', "%{$search}%")
                    ->orWhere('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")

                    // Search by department name
                    ->orWhereHas('department', function ($departmentQuery) use ($search) {
                        $departmentQuery->where(
                            'name',
                            'like',
                            "%{$search}%"
                        );
                    });
            });
        }

        // Filter by department ID
        if ($request->filled('department_id')) {
            $query->where(
                'department_id',
                $request->department_id
            );
        }

        // Filter by employment status
        if ($request->filled('employment_status')) {
            $query->where(
                'employment_status',
                $request->employment_status
            );
        }

        // 10 employees per page
        $employees = $query
            ->orderBy('id')
            ->paginate(10)
            ->withQueryString();

        return EmployeeResource::collection($employees)
            ->additional([
                'message' => 'Employees retrieved successfully'
            ]);
    }


    /**
     * Create employee.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'department_id' =>
                'required|exists:departments,id',

            'employee_number' =>
                'required|string|max:30|unique:employees,employee_number',

            'first_name' =>
                'required|string|max:80',

            'last_name' =>
                'required|string|max:80',

            'email' =>
                'required|email|unique:employees,email',

            'position' =>
                'required|string|max:100',

            'employment_status' =>
                'required|in:Active,Inactive',
        ]);

        $employee = Employee::create($validated);

        // Load department for JSON response
        $employee->load('department');

        return (new EmployeeResource($employee))
            ->additional([
                'message' => 'Employee created successfully'
            ])
            ->response()
            ->setStatusCode(201);
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

        return (new EmployeeResource($employee))
            ->additional([
                'message' => 'Employee retrieved successfully'
            ]);
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
            'department_id' =>
                'sometimes|exists:departments,id',

            'employee_number' => [
                'sometimes',
                'string',
                'max:30',
                Rule::unique('employees', 'employee_number')
                    ->ignore($employee->id),
            ],

            'first_name' =>
                'sometimes|string|max:80',

            'last_name' =>
                'sometimes|string|max:80',

            'email' => [
                'sometimes',
                'email',
                Rule::unique('employees', 'email')
                    ->ignore($employee->id),
            ],

            'position' =>
                'sometimes|string|max:100',

            'employment_status' =>
                'sometimes|in:Active,Inactive',
        ]);

        $employee->update($validated);

        // Reload updated employee and department
        $employee->load('department');

        return (new EmployeeResource($employee))
            ->additional([
                'message' => 'Employee updated successfully'
            ]);
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
        ], 200);
    }
}