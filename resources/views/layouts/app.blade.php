<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Travel Manager') - {{ config('app.name', 'Laravel') }}</title>
    
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
    @endif
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-gradient-to-br from-gray-50 via-indigo-50 to-purple-50 min-h-screen">
    <!-- Navigation -->
    <nav class="bg-white shadow-xl border-b-2 border-indigo-100 fixed top-0 left-0 right-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 p-2 rounded-lg">
                                <i class="fas fa-map-marked-alt text-white text-2xl"></i>
                            </div>
                            <span class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Travel Manager</span>
                        </a>
                    </div>
                    <div class="hidden sm:ml-10 sm:flex sm:space-x-1">
                        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-colors duration-200' }} inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium rounded-t-lg">
                            <i class="fas fa-home mr-2"></i> Dashboard
                        </a>
                        <a href="{{ route('web.places.index') }}" class="{{ request()->routeIs('web.places.*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-colors duration-200' }} inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium rounded-t-lg">
                            <i class="fas fa-map-marker-alt mr-2"></i> Places
                        </a>
                        <a href="{{ route('web.tours.index') }}" class="{{ request()->routeIs('web.tours.*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-colors duration-200' }} inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium rounded-t-lg">
                            <i class="fas fa-route mr-2"></i> Tours
                        </a>
                        <a href="{{ route('web.bookings.index') }}" class="{{ request()->routeIs('web.bookings.*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-colors duration-200' }} inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium rounded-t-lg">
                            <i class="fas fa-calendar-check mr-2"></i> Bookings
                        </a>
                        <a href="{{ route('web.reviews.index') }}" class="{{ request()->routeIs('web.reviews.*') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 transition-colors duration-200' }} inline-flex items-center px-4 py-2 border-b-2 text-sm font-medium rounded-t-lg">
                            <i class="fas fa-star mr-2"></i> Reviews
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <main class="py-8 pt-28">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-400 p-4 rounded-lg shadow-lg animate-slide-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check-circle text-green-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-400 p-4 rounded-lg shadow-lg animate-slide-in">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <i class="fas fa-exclamation-circle text-red-400 text-xl"></i>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <script>
        const API_BASE_URL = '/api';
        
        async function apiRequest(endpoint, method = 'GET', data = null) {
            const options = {
                method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                },
            };
            
            if (data) {
                options.body = JSON.stringify(data);
            }
            
            try {
                const response = await fetch(`${API_BASE_URL}${endpoint}`, options);
                const result = await response.json();
                
                if (!response.ok) {
                    throw new Error(result.message || 'An error occurred');
                }
                
                return result;
            } catch (error) {
                console.error('API Error:', error);
                throw error;
            }
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-20 right-4 z-50 p-4 rounded-lg shadow-2xl transform transition-all duration-300 ${
                type === 'success' ? 'bg-green-500' : 'bg-red-500'
            } text-white`;
            notification.innerHTML = `
                <div class="flex items-center space-x-2">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-circle'}"></i>
                    <span>${message}</span>
                </div>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateX(100%)';
                setTimeout(() => notification.remove(), 300);
            }, 3000);
        }
    </script>
    @stack('scripts')
</body>
</html>
