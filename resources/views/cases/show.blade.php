@extends('layouts.app')

@section('title', 'Case Details: ' . $case->case_number . ' - Jajal Medical')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Breadcrumb & Header -->
    <div class="flex items-center justify-between">
        <nav class="flex text-xs text-slate-500 space-x-2">
            <a href="{{ route('dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('cases.index') }}" class="hover:text-blue-600">Cases</a>
            <span>/</span>
            <span class="text-slate-800 font-semibold">{{ $case->case_number }}</span>
        </nav>
        <a href="{{ route('cases.index') }}" class="text-xs text-slate-600 hover:text-slate-900 bg-white px-3 py-1.5 rounded-lg border border-slate-300 shadow-sm">
            &larr; Back to Directory
        </a>
    </div>

    <!-- Main Card -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <!-- Top Status Banner -->
        <div class="px-6 py-5 bg-slate-50 border-b border-slate-200 sm:flex sm:items-center sm:justify-between">
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900">{{ $case->case_number }}</h1>
                    @php
                        $badgeClasses = [
                            'Draft' => 'bg-slate-100 text-slate-700 border-slate-300',
                            'Planning' => 'bg-sky-100 text-sky-800 border-sky-300',
                            'In Review' => 'bg-amber-100 text-amber-800 border-amber-300',
                            'Approved' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                            'Completed' => 'bg-blue-100 text-blue-800 border-blue-300',
                            'Cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
                        ][$case->status] ?? 'bg-slate-100 text-slate-800';
                    @endphp
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold border {{ $badgeClasses }}">
                        {{ $case->status }}
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-1 font-mono">
                    Patient Reference: <strong class="text-slate-700">{{ $case->patient_code }}</strong> (De-identified Dummy Data)
                </p>
            </div>

            <!-- Role-based Action Buttons -->
            <div class="mt-4 sm:mt-0 flex items-center gap-2">
                @can('update', $case)
                <a href="{{ route('cases.edit', $case) }}" class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 rounded-lg border border-slate-300 shadow-sm transition">
                    <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                    Edit Case
                </a>
                @endcan

                @can('delete', $case)
                <form action="{{ route('cases.destroy', $case) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete Medical Case {{ $case->case_number }}?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center px-3.5 py-2 text-sm font-medium text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-lg border border-rose-200 shadow-sm transition">
                        <svg class="w-4 h-4 mr-1.5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </form>
                @endcan
            </div>
        </div>

        <!-- Case Specifications Grid -->
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Left Column: Clinical Details -->
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Operating Surgeon</span>
                    <span class="text-base font-semibold text-slate-900">{{ $case->surgeon_name }}</span>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Implant Specification</span>
                    <div class="mt-1 p-3 bg-blue-50/50 rounded-lg border border-blue-100">
                        <span class="text-sm font-medium text-blue-950">{{ $case->implant_type }}</span>
                    </div>
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Priority Classification</span>
                    @php
                        $pColor = [
                            'High' => 'bg-rose-50 text-rose-700 border-rose-200',
                            'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                            'Low' => 'bg-slate-50 text-slate-700 border-slate-200',
                        ][$case->priority] ?? 'bg-slate-50 text-slate-700';
                    @endphp
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold border mt-1 {{ $pColor }}">
                        {{ $case->priority }} Priority
                    </span>
                </div>
            </div>

            <!-- Right Column: Schedule & Audit Trail -->
            <div class="space-y-4">
                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Scheduled Surgery Date</span>
                    @if ($case->surgery_date)
                        <div class="text-base font-semibold text-slate-900 mt-0.5">
                            {{ $case->surgery_date->format('l, F j, Y') }}
                        </div>
                        <span class="text-xs text-blue-600 font-medium">
                            ({{ $case->surgery_date->diffForHumans() }})
                        </span>
                    @else
                        <span class="text-sm text-slate-400 italic">No surgical date confirmed yet</span>
                    @endif
                </div>

                <div>
                    <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider">Created By</span>
                    <span class="text-sm font-medium text-slate-800">
                        {{ $case->creator ? $case->creator->name : 'System Administrator' }}
                        @if ($case->creator)
                            <span class="text-xs text-slate-500 font-mono">({{ $case->creator->role }})</span>
                        @endif
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 pt-2 border-t border-slate-100 text-xs text-slate-500">
                    <div>
                        <span class="block font-medium text-slate-400">Created At:</span>
                        <span>{{ $case->created_at ? $case->created_at->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
                    <div>
                        <span class="block font-medium text-slate-400">Last Modified:</span>
                        <span>{{ $case->updated_at ? $case->updated_at->format('M d, Y H:i') : 'N/A' }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Clinical Notes Box -->
        @if ($case->notes)
        <div class="px-6 pb-6">
            <span class="block text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1.5">Clinical Case Notes</span>
            <div class="bg-slate-50 rounded-lg p-4 text-sm text-slate-700 border border-slate-200 leading-relaxed">
                {{ $case->notes }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
