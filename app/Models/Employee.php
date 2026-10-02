<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Department;

class Employee extends Model
{
    protected $fillable = [
        'employee_number',
        'first_name',
        'last_name',
        'email',
        'position',
        'department_id',
        'employment_status'
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}