<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Seed the employees table with baseline records.
     */
    public function run(): void
    {
        $employees = [
            ['name' => 'Jane Employee', 'email' => 'jane.employee@example.com'],
            ['name' => 'John Employee', 'email' => 'john.employee@example.com'],
            ['name' => 'Mary Employee', 'email' => 'mary.employee@example.com'],
        ];

        foreach ($employees as $employee) {
            Employee::query()->firstOrCreate(
                ['email' => $employee['email']],
                ['name' => $employee['name']],
            );
        }
    }
}
