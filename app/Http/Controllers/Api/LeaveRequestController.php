<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ListLeaveRequest;
use App\Http\Requests\StoreLeaveRequest;
use App\Http\Resources\LeaveRequestResource;
use App\Services\Leave\LeaveFilingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeaveRequestController extends Controller
{
    public function __construct(
        private readonly LeaveFilingService $leaveFilingService,
    ) {
    }

    /**
     * Display a listing of leave requests.
     */
    public function index(ListLeaveRequest $request): AnonymousResourceCollection
    {
        $validated = $request->validated();
        $leaveRequests = $this->leaveFilingService->listLeaves($validated['employee_id'] ?? null);

        return LeaveRequestResource::collection($leaveRequests);
    }

    /**
     * Store a newly filed leave request.
     */
    public function store(StoreLeaveRequest $request): JsonResponse
    {
        $leaveRequest = $this->leaveFilingService->fileLeave($request->validated());

        return (new LeaveRequestResource($leaveRequest))
            ->response()
            ->setStatusCode(201);
    }
}
