<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Services\Employee\EmployeeService;
use Illuminate\Http\JsonResponse;

class EmployeeController extends Controller
{
    public function __construct(
        private readonly EmployeeService $employeeService,
    ) {
    }

    /**
     * Display a listing of employees.
     */
    public function index(): JsonResponse
    {
        $employees = $this->employeeService->listEmployees();

        return response()->json([
            'data' => $employees->map(static fn ($employee): array => [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'created_at' => $employee->created_at?->toISOString(),
            ])->all(),
        ]);
    }

    /**
     * Store a newly created employee.
     */
    public function store(StoreEmployeeRequest $request): JsonResponse
    {
        $employee = $this->employeeService->createEmployee($request->validated());

        return response()->json([
            'data' => [
                'id' => $employee->id,
                'name' => $employee->name,
                'email' => $employee->email,
                'created_at' => $employee->created_at?->toISOString(),
            ],
        ], 201);
    }
}
