@extends('layouts.app')

@section('title', 'Register New Medical Case - Jajal Medical')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <nav class="flex text-xs text-slate-500 space-x-2">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('cases.index') }}" class="hover:text-blue-600">Cases</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">New Case Registration</span>
        </nav>
        <a href="{{ route('cases.index') }}" class="text-xs text-slate-600 hover:text-slate-900 bg-white px-3 py-1.5 rounded-lg border border-slate-300 shadow-sm">
            &larr; Back to Directory
        </a>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-900">Register New Medical Case</h2>
            <p class="text-xs text-slate-500">Enter surgical implant and clinical planning details. Patient codes must use de-identified dummy data only.</p>
        </div>

        <form action="{{ route('cases.store') }}" method="POST" class="p-6 space-y-6">
            @csrf

            <!-- Section 1: Case & Patient Identifiers -->
            <div>
                <h3 class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-3">1. Case Identifiers</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Case Number -->
                    <div>
                        <label for="case_number" class="block text-xs font-medium text-slate-700 mb-1">
                            Case Number <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="case_number" id="case_number"
                            value="{{ old('case_number', 'CAS-2025-' . rand(2000, 9999)) }}"
                            placeholder="e.g. CAS-2025-0150" required
                            class="w-full text-sm rounded-lg border @error('case_number') border-rose-500 bg-rose-50/20 @else border-slate-300 @enderror px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('case_number')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-slate-400 mt-1">Must be unique across all cases.</p>
                    </div>

                    <!-- Patient Reference Code -->
                    <div>
                        <label for="patient_code" class="block text-xs font-medium text-slate-700 mb-1">
                            Patient Reference Code <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="patient_code" id="patient_code"
                            value="{{ old('patient_code', 'PT-' . rand(10000, 99999)) }}"
                            placeholder="e.g. PT-49102" required
                            class="w-full text-sm rounded-lg border @error('patient_code') border-rose-500 bg-rose-50/20 @else border-slate-300 @enderror px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 font-mono">
                        @error('patient_code')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-amber-600 mt-1">Synthetic/dummy code only (no real names or PHI).</p>
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <!-- Section 2: Clinical Team & Implant Specification -->
            <div>
                <h3 class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-3">2. Clinical & Implant Specifications</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Surgeon Name -->
                    <div>
                        <label for="surgeon_name" class="block text-xs font-medium text-slate-700 mb-1">
                            Operating Surgeon Name <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="surgeon_name" id="surgeon_name"
                            value="{{ old('surgeon_name') }}"
                            placeholder="e.g. Dr. Sarah Jenkins, MD" required
                            class="w-full text-sm rounded-lg border @error('surgeon_name') border-rose-500 bg-rose-50/20 @else border-slate-300 @enderror px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('surgeon_name')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Implant Type -->
                    <div>
                        <label for="implant_type" class="block text-xs font-medium text-slate-700 mb-1">
                            Implant Type Specification <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="implant_type" id="implant_type"
                            value="{{ old('implant_type') }}"
                            placeholder="e.g. Titanium Cranial Reconstruction Mesh" required
                            class="w-full text-sm rounded-lg border @error('implant_type') border-rose-500 bg-rose-50/20 @else border-slate-300 @enderror px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500">
                        @error('implant_type')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <hr class="border-slate-200">

            <!-- Section 3: Status, Priority & Schedule -->
            <div>
                <h3 class="text-xs font-semibold text-blue-700 uppercase tracking-wider mb-3">3. Status & Scheduling</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-xs font-medium text-slate-700 mb-1">
                            Case Status <span class="text-rose-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full text-sm rounded-lg border @error('status') border-rose-500 @else border-slate-300 @enderror px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white">
                            @foreach (['Draft', 'Planning', 'In Review', 'Approved', 'Completed', 'Cancelled'] as $st)
                                <option value="{{ $st }}" {{ old('status', 'Planning') === $st ? 'selected' : '' }}>
                                    {{ $st }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Priority -->
                    <div>
                        <label for="priority" class="block text-xs font-medium text-slate-700 mb-1">
                            Priority Level <span class="text-rose-500">*</span>
                        </label>
                        <select name="priority" id="priority" required
                            class="w-full text-sm rounded-lg border @error('priority') border-rose-500 @else border-slate-300 @enderror px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white">
                            @foreach (['Low', 'Medium', 'High'] as $pr)
                                <option value="{{ $pr }}" {{ old('priority', 'Medium') === $pr ? 'selected' : '' }}>
                                    {{ $pr }} Priority
                                </option>
                            @endforeach
                        </select>
                        @error('priority')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Surgery Date -->
                    <div>
                        <label for="surgery_date" class="block text-xs font-medium text-slate-700 mb-1">
                            Optional Surgery Date
                        </label>
                        <input type="date" name="surgery_date" id="surgery_date"
                            value="{{ old('surgery_date') }}"
                            class="w-full text-sm rounded-lg border @error('surgery_date') border-rose-500 @else border-slate-300 @enderror px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500 bg-white">
                        @error('surgery_date')
                            <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Notes -->
            <div>
                <label for="notes" class="block text-xs font-medium text-slate-700 mb-1">
                    Clinical Notes & Anatomical Sizing Details
                </label>
                <textarea name="notes" id="notes" rows="3"
                    placeholder="Enter anatomical defect notes, screw trajectories, or special sterilization requirements..."
                    class="w-full text-sm rounded-lg border border-slate-300 px-3.5 py-2.5 focus:outline-none focus:ring-1 focus:ring-blue-500">{{ old('notes') }}</textarea>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('cases.index') }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 rounded-lg transition">
                    Cancel
                </a>
                <button type="submit" class="px-5 py-2 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 rounded-lg shadow-sm transition">
                    Save Medical Case
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
