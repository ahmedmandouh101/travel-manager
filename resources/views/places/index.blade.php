@extends('layouts.app')

@section('title', 'Places')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Places</h1>
                <p class="text-lg text-gray-600">Manage tourist places and destinations</p>
            </div>
            <button onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105">
                <i class="fas fa-plus mr-2"></i> Add New Place
            </button>
        </div>
    </div>

    <!-- Places Grid -->
    <div id="places-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Loading State -->
        <div class="col-span-full text-center py-12">
            <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
            <p class="mt-4 text-gray-600">Loading places...</p>
        </div>
    </div>
</div>



<!-- Create/Edit Modal -->
<div id="place-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity duration-300" onclick="closeModal()"></div>
        <div class="inline-block align-bottom bg-white rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="place-form" onsubmit="savePlace(event)">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4">
                    <h3 class="text-xl font-bold text-white" id="modal-title">
                        <i class="fas fa-map-marker-alt mr-2"></i>Add Place
                    </h3>
                </div>
                <div class="bg-white px-6 py-6">
                    <input type="hidden" id="place-id">
                    <div class="space-y-5">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-signature mr-2 text-indigo-600"></i>Name
                            </label>
                            <input type="text" id="place-name" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4" placeholder="Enter place name">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-tag mr-2 text-indigo-600"></i>Type
                            </label>
                            <select id="place-type" required class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4">
                                <option value="">Select type</option>
                                <option value="hotel">Hotel</option>
                                <option value="restaurant">Restaurant</option>
                                <option value="entertainment">Entertainment</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-align-left mr-2 text-indigo-600"></i>Description
                            </label>
                            <textarea id="place-description" rows="4" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4" placeholder="Enter description"></textarea>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-map-marker-alt mr-2 text-indigo-600"></i>Address
                            </label>
                            <input type="text" id="place-address" class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm transition-all duration-200 border-2 py-3 px-4" placeholder="Enter address">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-6 py-4 flex justify-end space-x-3">
                    <button type="button" onclick="closeModal()" class="inline-flex items-center justify-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-200">
                        <i class="fas fa-times mr-2"></i>Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105">
                        <i class="fas fa-save mr-2"></i>Save Place
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@push('scripts')
<script>
    let places = [];
    let editingId = null;

    function getTypeBadge(type) {
        const baseClass = 'inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold';
        const badges = {
            'hotel': `${baseClass} bg-blue-100 text-blue-800`,
            'restaurant': `${baseClass} bg-green-100 text-green-800`,
            'entertainment': `${baseClass} bg-purple-100 text-purple-800`
        };
        return badges[type] || `${baseClass} bg-gray-100 text-gray-800`;
    }

    function getTypeIcon(type) {
        const icons = {
            'hotel': 'fa-hotel',
            'restaurant': 'fa-utensils',
            'entertainment': 'fa-theater-masks'
        };
        return icons[type] || 'fa-map-marker-alt';
    }

    async function loadPlaces() {
        try {
            places = await apiRequest('/places');
            renderPlaces();
        } catch (error) {
            document.getElementById('places-container').innerHTML = `
                <div class="col-span-full bg-red-50 border-l-4 border-red-400 p-4 rounded-lg">
                    <div class="flex">
                        <i class="fas fa-exclamation-circle text-red-400 text-xl mr-3"></i>
                        <p class="text-red-700">Error loading places: ${error.message}</p>
                    </div>
                </div>
            `;
        }
    }

    function renderPlaces() {
        const container = document.getElementById('places-container');
        if (places.length === 0) {
            container.innerHTML = `
                <div class="col-span-full text-center py-12">
                    <div class="bg-white rounded-2xl shadow-lg p-8 inline-block">
                        <i class="fas fa-map-marker-alt text-gray-300 text-6xl mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">No places found</h3>
                        <p class="text-gray-600 mb-4">Get started by adding your first place</p>
                        <button onclick="openCreateModal()" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-md hover:shadow-lg transform transition-all duration-200 hover:scale-105">
                            <i class="fas fa-plus mr-2"></i>Add Place
                        </button>
                    </div>
                </div>
            `;
            return;
        }
        
        container.innerHTML = places.map(place => `
            <div class="bg-white rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl hover:-translate-y-1">
                <div class="bg-gradient-to-r from-indigo-500 to-purple-500 p-6">
                    <div class="flex items-center justify-between">
                        <div class="bg-white bg-opacity-20 p-3 rounded-xl">
                            <i class="fas ${getTypeIcon(place.type)} text-white text-2xl"></i>
                        </div>
                        <span class="${getTypeBadge(place.type)}">${place.type.charAt(0).toUpperCase() + place.type.slice(1)}</span>
                    </div>
                </div>
                <div class="p-6">
                    <h3 class="text-xl font-bold text-gray-900 mb-2">${place.name}</h3>
                    ${place.description ? `<p class="text-gray-600 text-sm mb-4 line-clamp-2">${place.description}</p>` : ''}
                    ${place.address ? `
                        <div class="flex items-center text-gray-500 text-sm mb-4">
                            <i class="fas fa-map-marker-alt mr-2"></i>
                            <span class="truncate">${place.address}</span>
                        </div>
                    ` : ''}
                    <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                        <div class="flex items-center text-gray-600">
                            <i class="fas fa-route mr-2"></i>
                            <span class="text-sm font-medium">${place.tours ? place.tours.length : 0} Tours</span>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editPlace(${place.id})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button onclick="deletePlace(${place.id})" class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all duration-200">
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
        document.getElementById('modal-title').innerHTML = '<i class="fas fa-plus mr-2"></i>Add Place';
        document.getElementById('place-form').reset();
        document.getElementById('place-id').value = '';
        document.getElementById('place-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        document.getElementById('place-modal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    async function editPlace(id) {
        const place = places.find(p => p.id === id);
        if (!place) return;
        
        editingId = id;
        document.getElementById('modal-title').innerHTML = '<i class="fas fa-edit mr-2"></i>Edit Place';
        document.getElementById('place-id').value = place.id;
        document.getElementById('place-name').value = place.name;
        document.getElementById('place-type').value = place.type;
        document.getElementById('place-description').value = place.description || '';
        document.getElementById('place-address').value = place.address || '';
        document.getElementById('place-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    async function savePlace(event) {
        event.preventDefault();
        const submitBtn = event.target.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Saving...';
        
        const formData = {
            name: document.getElementById('place-name').value,
            type: document.getElementById('place-type').value,
            description: document.getElementById('place-description').value,
            address: document.getElementById('place-address').value,
        };

        try {
            if (editingId) {
                await apiRequest(`/places/${editingId}`, 'PUT', formData);
                if (typeof showNotification === 'function') showNotification('Place updated successfully!', 'success');
            } else {
                await apiRequest('/places', 'POST', formData);
                if (typeof showNotification === 'function') showNotification('Place created successfully!', 'success');
            }
            closeModal();
            loadPlaces();
        } catch (error) {
            if (typeof showNotification === 'function') {
                showNotification('Error: ' + error.message, 'error');
            } else {
                alert('Error saving place: ' + error.message);
            }
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }

    async function deletePlace(id) {
        const place = places.find(p => p.id === id);
        if (!confirm(`Are you sure you want to delete "${place.name}"?`)) return;
        
        try {
            await apiRequest(`/places/${id}`, 'DELETE');
            if (typeof showNotification === 'function') showNotification('Place deleted successfully!', 'success');
            loadPlaces();
        } catch (error) {
            if (typeof showNotification === 'function') {
                showNotification('Error: ' + error.message, 'error');
            } else {
                alert('Error deleting place: ' + error.message);
            }
        }
    }

    loadPlaces();

    // Check for action=create in URL
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'create') {
        setTimeout(() => {
            if (typeof openCreateModal === 'function') {
                openCreateModal();
                // Clean URL
                const newUrl = window.location.protocol + "//" + window.location.host + window.location.pathname;
                window.history.replaceState({path: newUrl}, '', newUrl);
            }
        }, 500);
    }
</script>
@endpush
