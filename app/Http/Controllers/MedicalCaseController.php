<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMedicalCaseRequest;
use App\Http\Requests\UpdateMedicalCaseRequest;
use App\Models\MedicalCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalCaseController extends Controller
{
    /**
     * Display the medical case management dashboard.
     * Shows counts for Total Cases, Planning, In Review, Approved, and Completed.
     */
    public function dashboard()
    {
        $metrics = [
            'total' => MedicalCase::count(),
            'draft' => MedicalCase::where('status', 'Draft')->count(),
            'planning' => MedicalCase::where('status', 'Planning')->count(),
            'in_review' => MedicalCase::where('status', 'In Review')->count(),
            'approved' => MedicalCase::where('status', 'Approved')->count(),
            'completed' => MedicalCase::where('status', 'Completed')->count(),
            'cancelled' => MedicalCase::where('status', 'Cancelled')->count(),
        ];

        $recentCases = MedicalCase::with('creator')
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('metrics', 'recentCases'));
    }

    /**
     * Display a listing of medical cases.
     * Displays 10 cases per page with search and status filtering.
     */
    public function index(Request $request)
    {
        $this->authorize('viewAny', MedicalCase::class);

        $search = $request->query('search');
        $status = $request->query('status');
        $priority = $request->query('priority');

        $cases = MedicalCase::with('creator')
            ->search($search)
            ->filterStatus($status)
            ->when($priority, function ($q) use ($priority) {
                if (in_array($priority, MedicalCase::PRIORITIES)) {
                    $q->where('priority', $priority);
                }
            })
            ->latest('id')
            ->paginate(10)
            ->withQueryString();

        return view('cases.index', compact('cases', 'search', 'status', 'priority'));
    }

    /**
     * Show the form for creating a new medical case.
     * Protected: Admin only.
     */
    public function create()
    {
        $this->authorize('create', MedicalCase::class);

        return view('cases.create');
    }

    /**
     * Store a newly created medical case in storage.
     * Protected: Admin only.
     */
    public function store(StoreMedicalCaseRequest $request)
    {
        $this->authorize('create', MedicalCase::class);

        $validated = $request->validated();
        $validated['created_by'] = Auth::id();

        $case = MedicalCase::create($validated);

        return redirect()->route('cases.show', $case)
            ->with('success', "Medical Case {$case->case_number} was created successfully.");
    }

    /**
     * Display the specified medical case.
     * Accessible by Admin and Viewer.
     */
    public function show(MedicalCase $case)
    {
        $this->authorize('view', $case);

        $case->load('creator');

        return view('cases.show', compact('case'));
    }

    /**
     * Show the form for editing the specified medical case.
     * Protected: Admin only.
     */
    public function edit(MedicalCase $case)
    {
        $this->authorize('update', $case);

        return view('cases.edit', compact('case'));
    }

    /**
     * Update the specified medical case in storage.
     * Protected: Admin only.
     */
    public function update(UpdateMedicalCaseRequest $request, MedicalCase $case)
    {
        $this->authorize('update', $case);

        $case->update($request->validated());

        return redirect()->route('cases.show', $case)
            ->with('success', "Medical Case {$case->case_number} was updated successfully.");
    }

    /**
     * Remove the specified medical case from storage.
     * Protected: Admin only.
     */
    public function destroy(MedicalCase $case)
    {
        $this->authorize('delete', $case);

        $caseNumber = $case->case_number;
        $case->delete();

        return redirect()->route('cases.index')
            ->with('success', "Medical Case {$caseNumber} has been permanently deleted.");
    }
}
