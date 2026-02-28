<?php

namespace Tests\Feature;

use App\Models\Employee;
use App\Models\Holiday;
use App\Models\LeaveRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaveFilingTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_files_valid_leave_and_deducts_holidays(): void
    {
        $employee = Employee::factory()->create();
        Holiday::factory()->create([
            'date' => '2026-03-04',
            'name' => 'Foundation Day',
        ]);

        $response = $this->postJson('/api/leave-requests', [
            'employee_id' => $employee->id,
            'start_date' => '2026-03-03',
            'end_date' => '2026-03-05',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.employee_id', $employee->id)
            ->assertJsonPath('data.start_date', '2026-03-03')
            ->assertJsonPath('data.end_date', '2026-03-05')
            ->assertJsonPath('data.deductible_days', 2);

        $savedLeave = LeaveRequest::query()->firstOrFail();
        $this->assertSame('2026-03-03', $savedLeave->start_date->toDateString());
        $this->assertSame('2026-03-05', $savedLeave->end_date->toDateString());
        $this->assertSame(2, $savedLeave->deductible_days);
    }

    public function test_it_rejects_overlapping_leave_requests(): void
    {
        $employee = Employee::factory()->create();

        LeaveRequest::factory()->create([
            'employee_id' => $employee->id,
            'start_date' => '2026-04-10',
            'end_date' => '2026-04-12',
            'deductible_days' => 3,
        ]);

        $response = $this->postJson('/api/leave-requests', [
            'employee_id' => $employee->id,
            'start_date' => '2026-04-11',
            'end_date' => '2026-04-13',
        ]);

        $response
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['start_date']);

        $this->assertDatabaseCount('leave_requests', 1);
    }

    public function test_it_lists_leave_requests_with_employee_filter(): void
    {
        $employeeA = Employee::factory()->create();
        $employeeB = Employee::factory()->create();

        LeaveRequest::factory()->create([
            'employee_id' => $employeeA->id,
            'start_date' => '2026-06-10',
            'end_date' => '2026-06-11',
            'deductible_days' => 2,
        ]);

        LeaveRequest::factory()->create([
            'employee_id' => $employeeB->id,
            'start_date' => '2026-06-12',
            'end_date' => '2026-06-13',
            'deductible_days' => 2,
        ]);

        $response = $this->getJson('/api/leave-requests?employee_id='.$employeeA->id);

        $response
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.employee_id', $employeeA->id);
    }

    public function test_it_handles_holiday_edge_case_with_zero_deductible_days(): void
    {
        $employee = Employee::factory()->create();

        Holiday::factory()->create([
            'date' => '2026-05-01',
            'name' => 'Labor Day',
        ]);

        $response = $this->postJson('/api/leave-requests', [
            'employee_id' => $employee->id,
            'start_date' => '2026-05-01',
            'end_date' => '2026-05-01',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('data.deductible_days', 0);

        $savedLeave = LeaveRequest::query()->firstOrFail();
        $this->assertSame('2026-05-01', $savedLeave->start_date->toDateString());
        $this->assertSame('2026-05-01', $savedLeave->end_date->toDateString());
        $this->assertSame(0, $savedLeave->deductible_days);
    }
}
