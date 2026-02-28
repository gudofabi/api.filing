<?php

namespace Database\Seeders;

use App\Models\Holiday;
use Illuminate\Database\Seeder;

class HolidaySeeder extends Seeder
{
    /**
     * Seed the holidays table with baseline records.
     */
    public function run(): void
    {
        $holidays = [
            ['date' => '2026-01-01', 'name' => 'New Year\'s Day'],
            ['date' => '2026-05-01', 'name' => 'Labor Day'],
            ['date' => '2026-12-25', 'name' => 'Christmas Day'],
        ];

        foreach ($holidays as $holiday) {
            Holiday::query()->firstOrCreate(
                ['date' => $holiday['date']],
                ['name' => $holiday['name']],
            );
        }
    }
}
