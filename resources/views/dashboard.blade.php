@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">Welcome to Travel Manager</h1>
        <p class="text-lg text-gray-600">Manage your travel destinations, tours, bookings, and reviews all in one place</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <!-- Places Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Places</p>
                    <p class="text-3xl font-bold text-gray-900" id="places-count">
                        <span class="inline-block animate-pulse bg-gray-200 h-8 w-16 rounded"></span>
                    </p>
                </div>
                <div class="bg-blue-100 p-4 rounded-xl">
                    <i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('web.places.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Tours Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Tours</p>
                    <p class="text-3xl font-bold text-gray-900" id="tours-count">
                        <span class="inline-block animate-pulse bg-gray-200 h-8 w-16 rounded"></span>
                    </p>
                </div>
                <div class="bg-purple-100 p-4 rounded-xl">
                    <i class="fas fa-route text-purple-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('web.tours.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-purple-600 hover:text-purple-800">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Bookings Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Bookings</p>
                    <p class="text-3xl font-bold text-gray-900" id="bookings-count">
                        <span class="inline-block animate-pulse bg-gray-200 h-8 w-16 rounded"></span>
                    </p>
                </div>
                <div class="bg-green-100 p-4 rounded-xl">
                    <i class="fas fa-calendar-check text-green-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('web.bookings.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-green-600 hover:text-green-800">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>

        <!-- Reviews Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 card-hover border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 mb-1">Reviews</p>
                    <p class="text-3xl font-bold text-gray-900" id="reviews-count">
                        <span class="inline-block animate-pulse bg-gray-200 h-8 w-16 rounded"></span>
                    </p>
                </div>
                <div class="bg-yellow-100 p-4 rounded-xl">
                    <i class="fas fa-star text-yellow-600 text-2xl"></i>
                </div>
            </div>
            <a href="{{ route('web.reviews.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-yellow-600 hover:text-yellow-800">
                View all <i class="fas fa-arrow-right ml-2"></i>
            </a>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-2xl shadow-lg p-6">
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Quick Actions</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('web.places.index') }}" onclick="event.preventDefault(); window.location.href='{{ route('web.places.index') }}'; setTimeout(() => { if (typeof openCreateModal === 'function') openCreateModal(); }, 100);" class="flex items-center justify-center p-4 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add Place
            </a>
            <a href="{{ route('web.tours.index') }}" onclick="event.preventDefault(); window.location.href='{{ route('web.tours.index') }}'; setTimeout(() => { if (typeof openCreateModal === 'function') openCreateModal(); }, 100);" class="flex items-center justify-center p-4 bg-gradient-to-r from-purple-500 to-purple-600 text-white rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add Tour
            </a>
            <a href="{{ route('web.bookings.index') }}" onclick="event.preventDefault(); window.location.href='{{ route('web.bookings.index') }}'; setTimeout(() => { if (typeof openCreateModal === 'function') openCreateModal(); }, 100);" class="flex items-center justify-center p-4 bg-gradient-to-r from-green-500 to-green-600 text-white rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add Booking
            </a>
            <a href="{{ route('web.reviews.index') }}" onclick="event.preventDefault(); window.location.href='{{ route('web.reviews.index') }}'; setTimeout(() => { if (typeof openCreateModal === 'function') openCreateModal(); }, 100);" class="flex items-center justify-center p-4 bg-gradient-to-r from-yellow-500 to-yellow-600 text-white rounded-xl hover:from-yellow-600 hover:to-yellow-700 transition-all duration-200 transform hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add Review
            </a>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    async function loadDashboardStats() {
        try {
            const [places, tours, bookings, reviews] = await Promise.all([
                apiRequest('/places'),
                apiRequest('/tours'),
                apiRequest('/bookings'),
                apiRequest('/reviews')
            ]);
            
            document.getElementById('places-count').innerHTML = places.length || 0;
            document.getElementById('tours-count').innerHTML = tours.length || 0;
            document.getElementById('bookings-count').innerHTML = bookings.length || 0;
            document.getElementById('reviews-count').innerHTML = reviews.length || 0;
        } catch (error) {
            console.error('Error loading dashboard stats:', error);
            document.getElementById('places-count').innerHTML = '0';
            document.getElementById('tours-count').innerHTML = '0';
            document.getElementById('bookings-count').innerHTML = '0';
            document.getElementById('reviews-count').innerHTML = '0';
        }
    }
    
    loadDashboardStats();
</script>
@endpush
