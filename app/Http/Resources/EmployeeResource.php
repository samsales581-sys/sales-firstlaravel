<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'department_id' => $this->department_id,

            'employee_number' => $this->employee_number,

            'first_name' => $this->first_name,

            'last_name' => $this->last_name,

            'email' => $this->email,

            'position' => $this->position,

            'employment_status' => $this->employment_status,

            'department' => [
                'id' => $this->department?->id,
                'name' => $this->department?->name,
                'code' => $this->department?->code,
            ],

            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}