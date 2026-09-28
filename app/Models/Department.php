<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Employee;

class Department extends Model
{
    protected $fillable = [
        'name',
        'code',
    ];

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }
}