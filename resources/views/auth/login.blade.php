@extends('layouts.app')

@section('title', 'Login - Jajal Medical Case Management System')

@section('content')
<div class="min-h-[70vh] flex flex-col justify-center py-6 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="flex justify-center">
            <div class="w-12 h-12 rounded-xl bg-blue-600 flex items-center justify-center text-white shadow-md">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                </svg>
            </div>
        </div>
        <h2 class="mt-4 text-center text-2xl font-bold tracking-tight text-slate-900">
            Sign in to Jajal Medical
        </h2>
        <p class="mt-1 text-center text-xs text-slate-500">
            Surgical Implant & Medical Case Management Portal
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-6 shadow-md border border-slate-200 sm:rounded-xl sm:px-10">
            <form class="space-y-5" action="{{ route('login.submit') }}" method="POST">
                @csrf

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Email Address
                    </label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                            value="{{ old('email', 'admin@jajal.com') }}"
                            class="block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>

                <!-- Password Field -->
                <div>
                    <label for="password" class="block text-xs font-semibold uppercase tracking-wider text-slate-700">
                        Password
                    </label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" autocomplete="current-password" required
                            value="password"
                            class="block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-slate-900 placeholder-slate-400 focus:border-blue-500 focus:outline-none focus:ring-1 focus:ring-blue-500 sm:text-sm">
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox"
                            class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                        <label for="remember" class="ml-2 block text-xs text-slate-600">
                            Remember this device
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit"
                        class="flex w-full justify-center rounded-lg bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition">
                        Sign in to Dashboard
                    </button>
                </div>
            </form>

            <!-- Demo Credentials Helper -->
            <div class="mt-6 border-t border-slate-100 pt-4">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">
                    Evaluation Demo Accounts:
                </div>
                <div class="space-y-2 text-xs">
                    <div class="bg-blue-50 p-2.5 rounded-lg border border-blue-100">
                        <div class="font-bold text-blue-900 flex justify-between">
                            <span>Admin Account (Full CRUD)</span>
                            <span class="text-blue-600 font-mono">role: admin</span>
                        </div>
                        <div class="text-slate-600 font-mono text-[11px] mt-0.5">
                            admin@jajal.com &bull; password
                        </div>
                    </div>
                    <div class="bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                        <div class="font-bold text-slate-800 flex justify-between">
                            <span>Viewer Account (Read-only)</span>
                            <span class="text-slate-500 font-mono">role: viewer</span>
                        </div>
                        <div class="text-slate-600 font-mono text-[11px] mt-0.5">
                            viewer@jajal.com &bull; password
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
