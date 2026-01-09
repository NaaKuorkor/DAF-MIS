<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>@yield('title', 'Staff Dashboard')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css', 'resources/js/staff.js'])
        <script src="https://kit.fontawesome.com/856b94abea.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<script src="//unpkg.com/alpinejs" defer></script>
        <style>
            body { font-family: 'Inter', sans-serif; }
            .sidebar-transition { transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
            .no-scrollbar::-webkit-scrollbar { display: none; }
            .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
            .nav-text { transition: opacity 0.2s; white-space: nowrap; }
            .collapsed .nav-text { opacity: 0; pointer-events: none; display: none; }
            .collapsed .nav-item { justify-content: center; padding-left: 0; padding-right: 0; }
            .collapsed .logo-text { display: none; }
            .collapsed .profile-text { display: none; }
            .content-fade { animation: fadeIn 0.3s ease-in; }
            @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        </style>
    </head>
    <body class="bg-gray-50 text-gray-900 h-screen overflow-hidden flex">
        @php
            $user = Auth::user();

            // Get user display name
            $userName = ($user->staff->fname . ' ' . $user->staff->lname);
            $userEmail = $user->email;

            // Portal title
            $portalTitle = 'Staff Portal';

            // Logout route
            $logoutRoute = 'staff.logout';

            // Search placeholder
            $searchPlaceholder =
                 'Search students, staff, or pages...';
        @endphp

        <!-- Sidebar -->
        <aside id="sidebar" class="bg-white border-r border-purple-200 h-full w-64 flex flex-col justify-between sidebar-transition z-20 relative flex-shrink-0">
            <div>
                <!-- Logo -->
                <div class="h-16 flex items-center px-6 border-b border-purple-100">
                    <div class="w-8 h-8 bg-purple-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-graduation-cap text-white text-lg"></i>
                    </div>
                    <span class="ml-3 font-semibold text-gray-800 tracking-tight logo-text">Staff Portal</span>
                </div>

                <!-- Navigation (populated by JS) -->
                <nav class="p-4 space-y-1" id="navigation-menu"></nav>
            </div>

            <!-- Bottom Section -->
            <div class="p-4 border-t border-purple-100">
                <button onclick="toggleSidebar()" class="w-full flex items-center justify-center p-2 mb-4 rounded-lg text-gray-400 hover:bg-purple-50 hover:text-purple-600 transition-colors">
                    <i id="collapseIcon" class="fas fa-angles-left text-xl transition-transform duration-300"></i>
                </button>

                <!-- Profile -->
                <button class="flex items-center w-full p-2 rounded-xl hover:bg-purple-50 transition-colors group text-left">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=9333ea&color=fff" alt="Profile" class="w-9 h-9 rounded-full border border-purple-200 flex-shrink-0">
                    <div class="ml-3 overflow-hidden profile-text">
                        <p class="text-sm font-medium text-gray-700 group-hover:text-gray-900 truncate">{{$userName }}</p>
                        <p class="text-xs text-gray-500 truncate">{{$userEmail}}</p>
                    </div>
                    <i class="fas fa-ellipsis-v ml-auto text-gray-400 profile-text"></i>
                </button>
            </div>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="flex-1 flex flex-col h-full overflow-hidden">

            <!-- Header -->
            <header class="h-16 bg-white/80 backdrop-blur-md border-b border-purple-200 flex items-center justify-between px-6 sticky top-0 z-10">
                <div class="flex items-center flex-1">
                    <div class="relative w-full max-w-md">
                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" placeholder="Search students, staff, or pages..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-purple-500/20 focus:border-purple-500 transition-all placeholder:text-gray-400">
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <button class="p-2 text-gray-500 hover:bg-purple-50 rounded-full transition-colors relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute top-2 right-2.5 w-2 h-2 bg-red-500 rounded-full border border-white"></span>
                    </button>
                    <button class="p-2 text-gray-500 hover:bg-purple-50 rounded-full transition-colors">
                        <i class="fas fa-question-circle text-xl"></i>
                    </button>
                    <div class="h-6 w-px bg-gray-200 mx-1"></div>

                    <form action="{{ route('staff.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8 no-scrollbar" id="dashboardContent">
                <div class="flex items-center justify-center h-full">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-4xl text-purple-600 mb-4"></i>
                        <p class="text-gray-500">Loading dashboard...</p>
                    </div>
                </div>
            </main>
        </div>

        <script>
            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const collapseIcon = document.getElementById('collapseIcon');

                if (sidebar.classList.contains('w-64')) {
                    sidebar.classList.remove('w-64');
                    sidebar.classList.add('w-20', 'collapsed');
                    collapseIcon.classList.remove('fa-angles-left');
                    collapseIcon.classList.add('fa-angles-right');
                } else {
                    sidebar.classList.remove('w-20', 'collapsed');
                    sidebar.classList.add('w-64');
                    collapseIcon.classList.remove('fa-angles-right');
                    collapseIcon.classList.add('fa-angles-left');
                }
            }
        </script>
    </body>
</html>

