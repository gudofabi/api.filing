<?php

namespace App\Services\Holiday;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class HolidayService
{
    /**
     * List holidays ordered by date.
     *
     * @return Collection<int, Holiday>
     */
    public function listHolidays(): Collection
    {
        return Holiday::query()
            ->orderBy('date')
            ->get();
    }

    /**
     * Create a new holiday.
     *
     * @param  array{date:string, name:string}  $payload
     */
    public function createHoliday(array $payload): Holiday
    {
        return DB::transaction(static fn (): Holiday => Holiday::query()->create($payload));
    }
}
