<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'DAF Web App')</title>
    
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
    <style>
        @keyframes blob {
            0% { transform: translate(0px, 0px) scale(1); }
            33% { transform: translate(30px, -50px) scale(1.1); }
            66% { transform: translate(-20px, 20px) scale(0.9); }
            100% { transform: translate(0px, 0px) scale(1); }
        }
        .animate-blob {
            animation: blob 10s infinite;
        }
        .animation-delay-2000 {
            animation-delay: 2s;
        }
        .animation-delay-4000 {
            animation-delay: 4s;
        }
        .animation-delay-6000 {
            animation-delay: 6s;
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900 h-screen overflow-hidden">

    <div class="h-full w-full flex">
        
        <!-- Left Side: Animated Background (60%) -->
        <div class="hidden lg:flex lg:w-3/5 relative h-full bg-purple-900 items-center justify-center overflow-hidden">
            <!-- Animated Background Layers -->
            <div class="absolute inset-0 bg-gradient-to-br from-purple-900 via-purple-800 to-indigo-900 animate-gradient-slow"></div>
            
            <!-- Blobs -->
            <div class="absolute top-0 -left-4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob"></div>
            <div class="absolute top-0 -right-4 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-20 w-96 h-96 bg-pink-600 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-4000"></div>
            <div class="absolute bottom-0 right-0 w-80 h-80 bg-blue-600 rounded-full mix-blend-multiply filter blur-3xl opacity-40 animate-blob animation-delay-6000"></div>

            <!-- Glass Overlay -->
            <div class="absolute inset-0 bg-white/5 backdrop-blur-[1px]"></div>

            <!-- Content Overlay -->
            <div class="relative z-10 text-center px-12 text-white">
                @hasSection('auth-left-content')
                    @yield('auth-left-content')
                @else
                    <!-- Default Content (Student) -->
                    <h1 class="text-8xl font-black tracking-tighter mb-4 drop-shadow-2xl">DAF</h1>
                    <p class="text-3xl font-light tracking-[0.2em] uppercase mb-6 border-b border-white/30 pb-4">Diaspora African Forum</p>
                    <p class="text-xl italic font-serif opacity-90">"We are the world"</p>
                @endif
            </div>
        </div>

        <!-- Right Side: Form (40%) -->
        <div class="w-full lg:w-2/5 h-full overflow-y-auto flex flex-col items-center bg-white px-8 py-12 lg:px-12">
            <div class="w-full max-w-md space-y-8 my-auto">
                
                <!-- Logo & Heading Section -->
                <div class="text-center">
                    <img src="{{ asset('images/DAFlogo.jpg') }}" alt="DAF Logo" class="mx-auto h-20 w-auto mb-6">
                    
                    @hasSection('heading')
                        <h2 class="text-3xl font-bold tracking-tight text-gray-900">
                            @yield('heading')
                        </h2>
                    @endif
                    
                    @hasSection('subheading')
                        <p class="mt-2 text-sm text-gray-600">
                            @yield('subheading')
                        </p>
                    @endif
                </div>

                <!-- Form Content -->
                <div class="mt-8">
                    @yield('content')
                </div>

            </div>
        </div>
    </div>
    
    @stack('scripts')
    <script>
        function togglePassword(inputId, icon) {
            const input = document.getElementById(inputId);
            const iconElement = icon.querySelector('i');
            
            if (input.type === "password") {
                input.type = "text";
                iconElement.classList.remove('fa-eye');
                iconElement.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                iconElement.classList.remove('fa-eye-slash');
                iconElement.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
