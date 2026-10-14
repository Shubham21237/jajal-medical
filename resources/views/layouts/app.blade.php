<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jajal Medical Case Management System')</title>
    <!-- Tailwind CSS CDN for simple, readable Blade presentation -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">
    <!-- Medical App Navigation Header -->
    <header class="bg-white border-b border-blue-100 shadow-sm sticky top-0 z-30">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-sm">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" />
                        </svg>
                    </div>
                    <div>
                        <a href="{{ route('dashboard') }}" class="font-bold text-lg text-slate-900 tracking-tight flex items-center gap-1.5">
                            <span>JAJAL</span>
                            <span class="text-blue-600 font-semibold text-xs px-2 py-0.5 bg-blue-50 rounded border border-blue-200">MEDICAL</span>
                        </a>
                        <p class="text-xs text-slate-500">Case Management System</p>
                    </div>
                </div>

                <!-- Navigation Links -->
                @auth
                <nav class="hidden md:flex items-center space-x-6 text-sm font-medium">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'text-blue-600 border-b-2 border-blue-600 pb-1 font-semibold' : 'text-slate-600 hover:text-blue-600 transition' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('cases.index') }}" class="{{ request()->routeIs('cases.*') ? 'text-blue-600 border-b-2 border-blue-600 pb-1 font-semibold' : 'text-slate-600 hover:text-blue-600 transition' }}">
                        Medical Cases
                    </a>
                </nav>

                <!-- User Profile & Role Badge -->
                <div class="flex items-center space-x-4">
                    <div class="text-right hidden sm:block">
                        <div class="text-sm font-semibold text-slate-900">{{ Auth::user()->name }}</div>
                        <div class="text-xs">
                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-semibold {{ Auth::user()->isAdmin() ? 'bg-blue-100 text-blue-800' : 'bg-slate-100 text-slate-700' }}">
                                Role: {{ ucfirst(Auth::user()->role) }}
                            </span>
                        </div>
                    </div>

                    <!-- Logout Button -->
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-3 py-1.5 text-xs font-medium text-slate-700 bg-slate-100 hover:bg-slate-200 hover:text-red-700 rounded-md transition border border-slate-300">
                            <svg class="w-4 h-4 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
                @endauth
            </div>
        </div>
    </header>

    <!-- Flash Notifications -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @if (session('success'))
            <div class="mb-5 bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-r shadow-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-emerald-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
                <div class="text-sm font-medium text-emerald-800">
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-red-600 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <div class="text-sm font-medium text-red-800">
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-5 bg-red-50 border-l-4 border-red-500 p-4 rounded-r shadow-sm">
                <div class="text-sm font-semibold text-red-800 mb-1">Please correct the following errors:</div>
                <ul class="list-disc list-inside text-xs text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Main Content -->
        @yield('content')
    </main>

    <!-- Clean Medical Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 text-center text-xs text-slate-500">
            &copy; {{ date('Y') }} Jajal Medical Case Management System &bull; De-identified dummy clinical data for development & evaluation.
        </div>
    </footer>
</body>
</html>
