<?php

namespace App\Services\Leave;

use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class LeaveFilingService
{
    public function __construct(
        private readonly LeaveOverlapChecker $overlapChecker,
        private readonly HolidayCalendar $holidayCalendar,
    ) {
    }

    /**
     * List leave requests with optional employee filter.
     *
     * @return Collection<int, LeaveRequest>
     */
    public function listLeaves(?int $employeeId = null): Collection
    {
        return LeaveRequest::query()
            ->when($employeeId !== null, static function ($query) use ($employeeId): void {
                $query->where('employee_id', $employeeId);
            })
            ->latest('id')
            ->get();
    }

    /**
     * File a leave request and return the persisted record.
     *
     * @param  array{employee_id:int, start_date:string, end_date:string}  $payload
     */
    public function fileLeave(array $payload): LeaveRequest
    {
        if ($this->overlapChecker->hasOverlap($payload['employee_id'], $payload['start_date'], $payload['end_date'])) {
            throw ValidationException::withMessages([
                'start_date' => ['Leave dates overlap with an existing leave request.'],
            ]);
        }

        $deductibleDays = $this->holidayCalendar->deductibleDays($payload['start_date'], $payload['end_date']);

        return DB::transaction(static function () use ($payload, $deductibleDays): LeaveRequest {
            return LeaveRequest::query()->create([
                'employee_id' => $payload['employee_id'],
                'start_date' => $payload['start_date'],
                'end_date' => $payload['end_date'],
                'deductible_days' => $deductibleDays,
            ]);
        });
    }
}
