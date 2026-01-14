<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <title>@yield('title', 'Student Dashboard')</title>
        <meta name="csrf-token" content="{{ csrf_token() }}">
        @vite(['resources/css/app.css', 'resources/js/student.js'])
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

            // Get user display name - check if student relationship exists
            $userName = 'User';
            if ($user->student) {
                $userName = ($user->student->fname . ' ' . $user->student->lname);
            } elseif ($user->email) {
                $userName = $user->email;
            }
            $userEmail = $user->email;

            // Portal title
            $portalTitle = 'Student Portal';

            // Logout route
            $logoutRoute = 'student.logout';

            // Search placeholder
                    @endphp

        <!-- Sidebar -->
        <aside id="sidebar" class="bg-purple-800 border-r border-purple-900 h-full w-64 flex flex-col justify-between sidebar-transition z-20 relative flex-shrink-0">
            <div>
                <!-- Logo -->
                <div class="h-16 flex items-center px-6 border-b border-purple-900">
                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-graduation-cap text-purple-600 text-lg"></i>
                    </div>
                    <span class="ml-3 font-semibold text-white tracking-tight logo-text">Student Portal</span>
                </div>

                <!-- Navigation (populated by JS) -->
                <nav class="p-4 space-y-1" id="studentNavigation-menu"></nav>
            </div>

            <!-- Bottom Section -->
            <div class="p-4 border-t border-purple-900">
                <button onclick="toggleSidebar()" class="w-full flex items-center justify-center p-2 mb-4 rounded-lg text-purple-200 hover:bg-purple-900 hover:text-white transition-colors">
                    <i id="collapseIcon" class="fas fa-angles-left text-xl transition-transform duration-300"></i>
                </button>

                <!-- Profile -->
                <button class="flex items-center w-full p-2 rounded-xl hover:bg-purple-900 transition-colors group text-left">
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($userName) }}&background=ffffff&color=9333ea" alt="Profile" class="w-9 h-9 rounded-full border border-purple-500 flex-shrink-0">
                    <div class="ml-3 overflow-hidden profile-text">
                        <p class="text-sm font-medium text-white group-hover:text-purple-100 truncate">{{$userName }}</p>
                        <p class="text-xs text-purple-200 truncate">{{$userEmail}}</p>
                    </div>
                    <i class="fas fa-ellipsis-v ml-auto text-purple-300 profile-text"></i>
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
                    <!-- Announcements Bell Icon -->
                    <div class="relative">
                        <button id="studentAnnouncementBellIcon" class="p-2 text-gray-500 hover:bg-purple-50 rounded-full transition-colors relative">
                            <i class="fas fa-bell text-xl"></i>
                            <span id="studentAnnouncementUnreadBadge" class="absolute top-1 right-1 min-w-[18px] h-[18px] bg-red-500 text-white text-[10px] font-bold rounded-full flex items-center justify-center px-1 border-2 border-white" style="display: none;">0</span>
                        </button>
                        
                        <!-- Announcements Dropdown -->
                        <div id="studentAnnouncementDropdown" class="hidden absolute right-0 mt-2 w-96 bg-white rounded-xl shadow-2xl border border-gray-200 z-50 max-h-[600px] overflow-hidden">
                            <div class="p-4 border-b border-gray-200 flex items-center justify-between bg-gradient-to-r from-purple-50 to-purple-100">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">Announcements</h3>
                                    <p class="text-xs text-gray-600">Unread notifications</p>
                                </div>
                                <button id="viewAllStudentAnnouncementsLink" class="text-xs font-medium text-purple-600 hover:text-purple-700 transition-colors">
                                    View All →
                                </button>
                            </div>
                            <div id="studentAnnouncementDropdownContent" class="max-h-[400px] overflow-y-auto">
                                <div class="p-6 text-center">
                                    <i class="fas fa-spinner fa-spin text-2xl text-purple-600 mb-3"></i>
                                    <p class="text-sm text-gray-500">Loading announcements...</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <button class="p-2 text-gray-500 hover:bg-purple-50 rounded-full transition-colors">
                        <i class="fas fa-question-circle text-xl"></i>
                    </button>
                    <div class="h-6 w-px bg-gray-200 mx-1"></div>

                    <form action="{{ route('student.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-600 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 md:p-8 no-scrollbar" id="studentDashboardContent">
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


