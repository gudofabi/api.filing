<?php

namespace App\Services\Leave;

use App\Models\LeaveRequest;

class LeaveOverlapChecker
{
    /**
     * Check if another leave request overlaps the given period.
     */
    public function hasOverlap(int $employeeId, string $startDate, string $endDate): bool
    {
        return LeaveRequest::query()
            ->where('employee_id', $employeeId)
            ->whereDate('start_date', '<=', $endDate)
            ->whereDate('end_date', '>=', $startDate)
            ->exists();
    }
}
