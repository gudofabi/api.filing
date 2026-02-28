<?php

namespace App\Services\Employee;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class EmployeeService
{
    /**
     * List employees ordered by latest created.
     *
     * @return Collection<int, Employee>
     */
    public function listEmployees(): Collection
    {
        return Employee::query()
            ->latest('id')
            ->get();
    }

    /**
     * Create a new employee.
     *
     * @param  array{name:string, email:string}  $payload
     */
    public function createEmployee(array $payload): Employee
    {
        return DB::transaction(static function () use ($payload): Employee {
            return Employee::query()->create($payload);
        });
    }
}
