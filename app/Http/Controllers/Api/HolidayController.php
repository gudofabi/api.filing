<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHolidayRequest;
use App\Services\Holiday\HolidayService;
use Illuminate\Http\JsonResponse;

class HolidayController extends Controller
{
    public function __construct(
        private readonly HolidayService $holidayService,
    ) {
    }

    /**
     * Display a listing of holidays.
     */
    public function index(): JsonResponse
    {
        $holidays = $this->holidayService->listHolidays();

        return response()->json([
            'data' => $holidays->map(static fn ($holiday): array => [
                'id' => $holiday->id,
                'date' => $holiday->date->toDateString(),
                'name' => $holiday->name,
                'created_at' => $holiday->created_at?->toISOString(),
            ])->all(),
        ]);
    }

    /**
     * Store a newly created holiday.
     */
    public function store(StoreHolidayRequest $request): JsonResponse
    {
        $holiday = $this->holidayService->createHoliday($request->validated());

        return response()->json([
            'data' => [
                'id' => $holiday->id,
                'date' => $holiday->date->toDateString(),
                'name' => $holiday->name,
                'created_at' => $holiday->created_at?->toISOString(),
            ],
        ], 201);
    }
}
