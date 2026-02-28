<?php

namespace App\Services\Leave;

use App\Models\Holiday;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class HolidayCalendar
{
    /**
     * Calculate leave days excluding holidays inside the leave range.
     */
    public function deductibleDays(string $startDate, string $endDate): int
    {
        $holidayDates = Holiday::query()
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->pluck('date')
            ->map(static fn ($date) => Carbon::parse($date)->toDateString())
            ->all();

        $holidayMap = array_fill_keys($holidayDates, true);

        $days = 0;
        $period = CarbonPeriod::create($startDate, $endDate);

        foreach ($period as $day) {
            if (! isset($holidayMap[$day->toDateString()])) {
                $days++;
            }
        }

        return $days;
    }
}
