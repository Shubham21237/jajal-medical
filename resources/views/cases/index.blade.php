@extends('layouts.app')

@section('title', 'Medical Cases Directory - Jajal Medical')

@section('content')
<div class="space-y-5">
    <!-- Top Bar: Title & Actions -->
    <div class="sm:flex sm:items-center sm:justify-between bg-white p-5 rounded-xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl font-bold text-slate-900 tracking-tight">Medical Cases Directory</h1>
            <p class="text-xs text-slate-500 mt-0.5">
                Showing {{ $cases->firstItem() ?? 0 }} to {{ $cases->lastItem() ?? 0 }} of {{ $cases->total() }} total medical cases
            </p>
        </div>
        <div class="mt-4 sm:mt-0 flex items-center gap-3">
            @can('create', App\Models\MedicalCase::class)
            <a href="{{ route('cases.create') }}" class="inline-flex items-center rounded-lg bg-blue-600 px-3.5 py-2 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 transition">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Create Case
            </a>
            @else
            <span class="inline-flex items-center px-3 py-1.5 rounded-lg bg-slate-100 text-slate-500 text-xs font-medium border border-slate-200">
                <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                Read-Only (Viewer Role)
            </span>
            @endcan
        </div>
    </div>

    <!-- Search & Filter Controls -->
    <div class="bg-white p-4 rounded-xl border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('cases.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Search Input -->
            <div class="md:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Search by case number, surgeon, or patient..."
                    class="w-full pl-9 pr-3 py-2 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-3">
                <select name="status" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">All Statuses</option>
                    @foreach (['Draft', 'Planning', 'In Review', 'Approved', 'Completed', 'Cancelled'] as $st)
                        <option value="{{ $st }}" {{ $status === $st ? 'selected' : '' }}>{{ $st }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Priority Filter -->
            <div class="md:col-span-2">
                <select name="priority" class="w-full py-2 px-3 text-sm rounded-lg border border-slate-300 focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 bg-white">
                    <option value="">All Priorities</option>
                    @foreach (['Low', 'Medium', 'High'] as $pr)
                        <option value="{{ $pr }}" {{ $priority === $pr ? 'selected' : '' }}>{{ $pr }} Priority</option>
                    @endforeach
                </select>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex gap-2">
                <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-3 rounded-lg text-sm transition">
                    Filter
                </button>
                @if ($search || $status || $priority)
                <a href="{{ route('cases.index') }}" class="bg-slate-100 hover:bg-slate-200 text-slate-700 font-medium py-2 px-3 rounded-lg text-sm transition text-center">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Medical Cases Table (10 per page) -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-slate-600 text-xs uppercase font-semibold">
                    <tr>
                        <th class="px-5 py-3.5 text-left">Case Number</th>
                        <th class="px-5 py-3.5 text-left">Surgeon</th>
                        <th class="px-5 py-3.5 text-left">Implant Type</th>
                        <th class="px-5 py-3.5 text-left">Status</th>
                        <th class="px-5 py-3.5 text-left">Priority</th>
                        <th class="px-5 py-3.5 text-left">Surgery Date</th>
                        <th class="px-5 py-3.5 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($cases as $case)
                    <tr class="hover:bg-blue-50/30 transition">
                        <!-- Case Number & Patient Reference -->
                        <td class="px-5 py-3.5">
                            <a href="{{ route('cases.show', $case) }}" class="font-bold text-blue-600 hover:text-blue-800 hover:underline">
                                {{ $case->case_number }}
                            </a>
                            <div class="text-[11px] font-mono text-slate-500">
                                {{ $case->patient_code }}
                            </div>
                        </td>

                        <!-- Surgeon -->
                        <td class="px-5 py-3.5 font-medium text-slate-900">
                            {{ $case->surgeon_name }}
                        </td>

                        <!-- Implant Type -->
                        <td class="px-5 py-3.5 text-slate-700 text-xs">
                            {{ $case->implant_type }}
                        </td>

                        <!-- Status Badge -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
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
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold border {{ $badgeClasses }}">
                                {{ $case->status }}
                            </span>
                        </td>

                        <!-- Priority Badge -->
                        <td class="px-5 py-3.5 whitespace-nowrap">
                            @php
                                $pClasses = [
                                    'High' => 'bg-rose-50 text-rose-700 border-rose-200',
                                    'Medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                    'Low' => 'bg-slate-100 text-slate-600 border-slate-200',
                                ][$case->priority] ?? 'bg-slate-50 text-slate-700';
                            @endphp
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium border {{ $pClasses }}">
                                {{ $case->priority }}
                            </span>
                        </td>

                        <!-- Surgery Date -->
                        <td class="px-5 py-3.5 whitespace-nowrap text-xs text-slate-600">
                            @if ($case->surgery_date)
                                <div class="font-medium text-slate-800">{{ $case->surgery_date->format('M d, Y') }}</div>
                                <div class="text-[11px] text-slate-400">{{ $case->surgery_date->diffForHumans() }}</div>
                            @else
                                <span class="text-slate-400 italic">Not scheduled</span>
                            @endif
                        </td>

                        <!-- Actions (Role-governed) -->
                        <td class="px-5 py-3.5 whitespace-nowrap text-right text-xs font-medium space-x-2">
                            <a href="{{ route('cases.show', $case) }}" class="text-blue-600 hover:text-blue-900 bg-blue-50 px-2 py-1 rounded border border-blue-200">
                                View
                            </a>

                            @can('update', $case)
                            <a href="{{ route('cases.edit', $case) }}" class="text-slate-700 hover:text-slate-900 bg-slate-100 px-2 py-1 rounded border border-slate-200">
                                Edit
                            </a>
                            @endcan

                            @can('delete', $case)
                            <form action="{{ route('cases.destroy', $case) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to permanently delete Medical Case {{ $case->case_number }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 px-2 py-1 rounded border border-red-200">
                                    Delete
                                </button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-slate-400 text-sm">
                            <div class="max-w-sm mx-auto">
                                <svg class="w-12 h-12 mx-auto text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="font-medium text-slate-700">No medical cases match your filters.</p>
                                <p class="text-xs text-slate-500 mt-1">Try resetting the search keyword or filter options.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 10 Per Page Pagination Controls -->
        @if ($cases->hasPages())
        <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50 flex items-center justify-between">
            <div class="text-xs text-slate-500">
                Displaying 10 cases per page (Page {{ $cases->currentPage() }} of {{ $cases->lastPage() }})
            </div>
            <div>
                {{ $cases->links() }}
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
