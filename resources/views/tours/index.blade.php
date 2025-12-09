@extends('layouts.app')

@section('title', 'Tours')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Tours</h1>
                <p class="text-lg text-gray-600">Manage tour packages and experiences</p>
            </div>
            <button onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add New Tour
            </button>
        </div>
    </div>

    <div id="tours-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <div class="col-span-full text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            <p class="mt-4 text-gray-600">Loading tours...</p>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="tour-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity duration-300" onclick="closeModal()"></div>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="tour-form" onsubmit="saveTour(event)">
                <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white" id="modal-title">
                        <i class="fas fa-route mr-2"></i>Add Tour
                    </h3>
                </div>
                <div class="bg-white px-6 py-6">
                    <input type="hidden" id="tour-id">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-heading mr-2 text-purple-600"></i>Title
                            </label>
                            <input type="text" id="tour-title" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4" placeholder="Enter tour title">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>Place
                            </label>
                            <select id="tour-place-id" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4">
                                <option value="">Select a place</option>
                            </select>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-dollar-sign mr-2 text-purple-600"></i>Price
                                </label>
                                <input type="number" step="0.01" id="tour-price" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4" placeholder="0.00">
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">
                                    <i class="fas fa-clock mr-2 text-purple-600"></i>Duration (hours)
                                </label>
                                <input type="number" id="tour-duration" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4" placeholder="Hours">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-purple-600"></i>Description
                            </label>
                            <textarea id="tour-description" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4" placeholder="Enter description"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Save Tour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let tours = [];
    let places = [];
    let editingId = null;

    async function loadTours() {
        try {
            tours = await apiRequest('/tours');
            renderTours();
        } catch (error) {
            document.getElementById('tours-container').innerHTML = `
                <div class="col-span-full bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                    <p class="text-red-700">Error loading tours: ${error.message}</p>
                </div>
            `;
        }
    }

    async function loadPlaces() {
        try {
            places = await apiRequest('/places');
            const select = document.getElementById('tour-place-id');
            select.innerHTML = '<option value="">Select a place</option>' + 
                places.map(p => `<option value="${p.id}">${p.name}</option>`).join('');
        } catch (error) {
            console.error('Error loading places:', error);
        }
    }

    function renderTours() {
        const container = document.getElementById('tours-container');
        if (tours.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="bg-white rounded-2xl shadow-lg p-8 inline-block">
                        <i class="fas fa-route text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No tours found</h3>
                        <button onclick="openCreateModal()" class="mt-4 inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>Add Tour
                        </button>
                    </div>
                </div>
            `;
            return;
        }
        
        container.innerHTML = tours.map(tour => `
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="bg-gradient-to-r from-purple-500 to-pink-500 p-6">
                    <div class="flex items-center justify-between">
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <i class="fas fa-route text-white text-2xl"></i>
                        </div>
                        <div class="text-right">
                            <div class="text-2xl font-bold text-white">$${parseFloat(tour.price).toFixed(2)}</div>
                            <div class="text-white text-sm opacity-90">per person</div>
                        </div>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">${tour.title}</h3>
                    ${tour.description ? `<p class="text-gray-600 text-sm mb-4 line-clamp-2">${tour.description}</p>` : ''}
                    <div class="space-y-2 mb-4">
                        ${tour.place ? `
                            <div class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-map-marker-alt mr-2 text-purple-600"></i>
                                <span>${tour.place.name}</span>
                            </div>
                        ` : ''}
                        ${tour.duration_hours ? `
                            <div class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-clock mr-2 text-purple-600"></i>
                                <span>${tour.duration_hours} hours</span>
                            </div>
                        ` : ''}
                    </div>
                    <div class="flex justify-end pt-4 border-t border-gray-200">
                        <div class="flex space-x-2">
                            <button onclick="editTour(${tour.id})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deleteTour(${tour.id})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `).join('');
    }

    function openCreateModal() {
        editingId = null;
        document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus mr-2"></i>Add Tour';
        document.getElementById('tour-form').reset();
        document.getElementById('tour-id').value = '';
        document.getElementById('tour-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('tour-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function editTour(id) {
        const tour = tours.find(t => t.id === id);
        if (!tour) return;
        
        editingId = id;
        document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Tour';
        document.getElementById('tour-id').value = tour.id;
        document.getElementById('tour-title').value = tour.title;
        document.getElementById('tour-place-id').value = tour.place_id || '';
        document.getElementById('tour-price').value = tour.price;
        document.getElementById('tour-duration').value = tour.duration_hours || '';
        document.getElementById('tour-description').value = tour.description || '';
        document.getElementById('tour-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    async function saveTour(event) {
        event.preventDefault();
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        const formData = {
            title: document.getElementById('tour-title').value,
            place_id: document.getElementById('tour-place-id').value || null,
            price: parseFloat(document.getElementById('tour-price').value),
            duration_hours: document.getElementById('tour-duration').value ? parseInt(document.getElementById('tour-duration').value) : null,
            description: document.getElementById('tour-description').value,
        };

        try {
            if (editingId) {
                await apiRequest(`/tours/${editingId}`, 'PUT', formData);
                if (typeof showNotification === 'function') showNotification('Tour updated successfully!', 'success');
            } else {
                await apiRequest('/tours', 'POST', formData);
                if (typeof showNotification === 'function') showNotification('Tour created successfully!', 'success');
            }
            closeModal();
            loadTours();
        } catch (error) {
            if (typeof showNotification === 'function') {
                showNotification('Error: ' + error.message, 'error');
            } else {
                alert('Error saving tour: ' + error.message);
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    async function deleteTour(id) {
        const tour = tours.find(t => t.id === id);
        if (!confirm(`Are you sure you want to delete "${tour.title}"?`)) return;
        
        try {
            await apiRequest(`/tours/${id}`, 'DELETE');
            if (typeof showNotification === 'function') showNotification('Tour deleted successfully!', 'success');
            loadTours();
        } catch (error) {
            if (typeof showNotification === 'function') {
                showNotification('Error: ' + error.message, 'error');
            } else {
                alert('Error deleting tour: ' + error.message);
            }
        }
    }

    loadPlaces();
    loadTours();
</script>
@endpush
