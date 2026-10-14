@extends('layouts.app')

@section('title', 'Clinical Dashboard - Jajal Medical')

@section('content')
<div class="space-y-6">
    <!-- Welcome Header & Quick Action -->
    <div class="md:flex md:items-center md:justify-between bg-white p-6 rounded-xl border border-slate-200 shadow-sm">
        <div class="min-w-0 flex-1">
            <h1 class="text-2xl font-bold leading-7 text-slate-900 sm:truncate sm:tracking-tight flex items-center gap-2">
                Medical Case Overview
                <span class="text-xs px-2.5 py-1 bg-blue-100 text-blue-800 font-semibold rounded-full">Active Portal</span>
            </h1>
            <p class="mt-1 text-sm text-slate-500">
                Real-time tracking of patient implant cases, clinical review stages, and surgical schedules.
            </p>
        </div>
        <div class="mt-4 flex md:ml-4 md:mt-0 gap-3">
            <a href="{{ route('cases.index') }}" class="inline-flex items-center rounded-lg bg-white px-3.5 py-2 text-sm font-medium text-slate-700 shadow-sm border border-slate-300 hover:bg-slate-50 transition">
                <svg class="w-4 h-4 mr-1.5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                </svg>
                Browse All Cases
            </a>
            @can('create', App\Models\MedicalCase::class)
            <a href="{{ route('cases.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create New Case
            </a>
            @endcan
        </div>
    </div>

    <!-- Required Status Metric Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-5">
        <!-- Total Cases -->
        <div class="bg-white overflow-hidden rounded-xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <dt class="text-xs font-medium text-slate-500 uppercase tracking-wider">Total Cases</dt>
                    <dd class="text-2xl font-bold text-slate-900">{{ $metrics['total'] }}</dd>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">
                All active & archived cases
            </div>
        </div>

        <!-- Planning -->
        <div class="bg-white overflow-hidden rounded-xl border border-sky-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-sky-50 flex items-center justify-center text-sky-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <dt class="text-xs font-medium text-sky-700 uppercase tracking-wider">Planning</dt>
                    <dd class="text-2xl font-bold text-sky-900">{{ $metrics['planning'] }}</dd>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-sky-100 text-xs text-sky-600">
                Pre-operative design & CAD
            </div>
        </div>

        <!-- In Review -->
        <div class="bg-white overflow-hidden rounded-xl border border-amber-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <dt class="text-xs font-medium text-amber-700 uppercase tracking-wider">In Review</dt>
                    <dd class="text-2xl font-bold text-amber-900">{{ $metrics['in_review'] }}</dd>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-amber-100 text-xs text-amber-600">
                Pending clinical sign-off
            </div>
        </div>

        <!-- Approved -->
        <div class="bg-white overflow-hidden rounded-xl border border-emerald-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-emerald-50 flex items-center justify-center text-emerald-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <dt class="text-xs font-medium text-emerald-700 uppercase tracking-wider">Approved</dt>
                    <dd class="text-2xl font-bold text-emerald-900">{{ $metrics['approved'] }}</dd>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-emerald-100 text-xs text-emerald-600">
                Cleared for manufacturing & OR
            </div>
        </div>

        <!-- Completed -->
        <div class="bg-white overflow-hidden rounded-xl border border-blue-200 p-5 shadow-sm hover:shadow-md transition">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <div class="ml-4 flex-1">
                    <dt class="text-xs font-medium text-blue-700 uppercase tracking-wider">Completed</dt>
                    <dd class="text-2xl font-bold text-blue-900">{{ $metrics['completed'] }}</dd>
                </div>
            </div>
            <div class="mt-3 pt-3 border-t border-blue-100 text-xs text-blue-600">
                Surgery performed
            </div>
        </div>
    </div>

    <!-- Recent Medical Cases Preview -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center bg-slate-50/50">
            <div>
                <h2 class="text-base font-semibold text-slate-900">Recent Cases</h2>
                <p class="text-xs text-slate-500">Latest medical case activity and assignments</p>
            </div>
            <a href="{{ route('cases.index') }}" class="text-xs font-semibold text-blue-600 hover:text-blue-800">
                View All &rarr;
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-500 text-xs uppercase font-medium">
                    <tr>
                        <th class="px-6 py-3 text-left">Case Number</th>
                        <th class="px-6 py-3 text-left">Patient Code</th>
                        <th class="px-6 py-3 text-left">Surgeon</th>
                        <th class="px-6 py-3 text-left">Implant Type</th>
                        <th class="px-6 py-3 text-left">Status</th>
                        <th class="px-6 py-3 text-left">Priority</th>
                        <th class="px-6 py-3 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($recentCases as $case)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-6 py-4 font-semibold text-blue-600">
                            <a href="{{ route('cases.show', $case) }}">{{ $case->case_number }}</a>
                        </td>
                        <td class="px-6 py-4 font-mono text-xs text-slate-600">
                            {{ $case->patient_code }}
                        </td>
                        <td class="px-6 py-4 text-slate-900">
                            {{ $case->surgeon_name }}
                        </td>
                        <td class="px-6 py-4 text-slate-600 text-xs">
                            {{ $case->implant_type }}
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $badgeClasses = [
                                    'Draft' => 'bg-slate-100 text-slate-700 border-slate-300',
                                    'Planning' => 'bg-sky-100 text-sky-800 border-sky-300',
                                    'In Review' => 'bg-amber-100 text-amber-800 border-amber-300',
                                    'Approved' => 'bg-emerald-100 text-emerald-800 border-emerald-300',
                                    'Completed' => 'bg-blue-100 text-blue-800 border-blue-300',
                                    'Cancelled' => 'bg-rose-100 text-rose-800 border-rose-300',
                                ][$case->status] ?? 'bg-slate-100 text-slate-800 border-slate-300';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $badgeClasses }}">
                                {{ $case->status }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $priorityColor = [
                                    'High' => 'text-rose-600 bg-rose-50 border-rose-200',
                                    'Medium' => 'text-amber-700 bg-amber-50 border-amber-200',
                                    'Low' => 'text-slate-600 bg-slate-50 border-slate-200',
                                ][$case->priority] ?? 'text-slate-600 bg-slate-50';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $priorityColor }}">
                                {{ $case->priority }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('cases.show', $case) }}" class="text-xs font-medium text-blue-600 hover:text-blue-900">
                                Details
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-8 text-center text-slate-400 text-sm">
                            No medical cases recorded yet.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
