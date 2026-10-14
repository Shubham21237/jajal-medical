<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalCase;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class MedicalCaseController extends Controller
{
    /**
     * GET /api/cases
     * List medical cases with optional search, status filtering, and pagination.
     */
    public function index(Request $request): JsonResponse
    {
        $search = $request->query('search');
        $status = $request->query('status');
        $perPage = (int) $request->query('per_page', 10);
        if ($perPage <= 0 || $perPage > 100) {
            $perPage = 10;
        }

        $cases = MedicalCase::with('creator:id,name,email,role')
            ->search($search)
            ->filterStatus($status)
            ->latest('id')
            ->paginate($perPage);

        return response()->json([
            'success' => true,
            'message' => 'Medical cases retrieved successfully.',
            'data' => $cases->items(),
            'meta' => [
                'current_page' => $cases->currentPage(),
                'last_page' => $cases->lastPage(),
                'per_page' => $cases->perPage(),
                'total' => $cases->total(),
            ],
        ], 200);
    }

    /**
     * POST /api/cases
     * Create a new medical case. Admin only.
     */
    public function store(Request $request): JsonResponse
    {
        // Role check
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Viewer accounts do not have permission to create medical cases.',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'case_number' => ['required', 'string', 'max:64', Rule::unique('medical_cases', 'case_number')],
            'patient_code' => ['required', 'string', 'max:64'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(MedicalCase::STATUSES)],
            'priority' => ['required', 'string', Rule::in(MedicalCase::PRIORITIES)],
            'surgery_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ], [
            'case_number.required' => 'Case Number is required.',
            'case_number.unique' => 'Duplicate Case Number: this number is already assigned.',
            'patient_code.required' => 'Patient Reference Code is required.',
            'surgeon_name.required' => 'Surgeon Name is required.',
            'implant_type.required' => 'Implant Type is required.',
            'status.required' => 'Status is required.',
            'status.in' => 'Status must be one of: Draft, Planning, In Review, Approved, Completed, Cancelled.',
            'priority.required' => 'Priority is required.',
            'priority.in' => 'Priority must be Low, Medium, or High.',
            'surgery_date.date' => 'Surgery date must be a valid date.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();
        $data['created_by'] = $request->user()->id;

        $case = MedicalCase::create($data);
        $case->load('creator:id,name,email,role');

        return response()->json([
            'success' => true,
            'message' => 'Medical case created successfully.',
            'data' => $case,
        ], 201);
    }

    /**
     * GET /api/cases/{id}
     * Retrieve single medical case details.
     */
    public function show(int $id): JsonResponse
    {
        $case = MedicalCase::with('creator:id,name,email,role')->find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'message' => "Medical Case with ID #{$id} was not found.",
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Medical case details retrieved successfully.',
            'data' => $case,
        ], 200);
    }

    /**
     * PUT /api/cases/{id}
     * Update an existing medical case. Admin only.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Viewer accounts do not have permission to edit medical cases.',
            ], 403);
        }

        $case = MedicalCase::find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'message' => "Medical Case with ID #{$id} was not found.",
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'case_number' => ['required', 'string', 'max:64', Rule::unique('medical_cases', 'case_number')->ignore($case->id)],
            'patient_code' => ['required', 'string', 'max:64'],
            'surgeon_name' => ['required', 'string', 'max:255'],
            'implant_type' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', Rule::in(MedicalCase::STATUSES)],
            'priority' => ['required', 'string', Rule::in(MedicalCase::PRIORITIES)],
            'surgery_date' => ['nullable', 'date'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error.',
                'errors' => $validator->errors(),
            ], 422);
        }

        $case->update($validator->validated());
        $case->load('creator:id,name,email,role');

        return response()->json([
            'success' => true,
            'message' => 'Medical case updated successfully.',
            'data' => $case,
        ], 200);
    }

    /**
     * DELETE /api/cases/{id}
     * Delete a medical case. Admin only.
     */
    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || !$request->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Forbidden: Viewer accounts do not have permission to delete medical cases.',
            ], 403);
        }

        $case = MedicalCase::find($id);

        if (!$case) {
            return response()->json([
                'success' => false,
                'message' => "Medical Case with ID #{$id} was not found.",
            ], 404);
        }

        $caseNumber = $case->case_number;
        $case->delete();

        return response()->json([
            'success' => true,
            'message' => "Medical Case {$caseNumber} deleted successfully.",
        ], 200);
    }
}
