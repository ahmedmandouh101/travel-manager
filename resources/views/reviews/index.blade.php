@extends('layouts.app')

@section('title', 'Reviews')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Reviews</h1>
                <p class="text-lg text-gray-600">View and manage customer reviews</p>
            </div>
            <button onclick="openCreateModal()" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Add New Review
            </button>
        </div>
    </div>

    <div class="mt-8 flow-root">
        <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                    <table class="min-w-full divide-y divide-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-6">User</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Item</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Rating</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Comment</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white" id="reviews-table-body">
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="review-modal" class="hidden fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="review-form" onsubmit="saveReview(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Add Review</h3>
                    <input type="hidden" id="review-id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">User</label>
                        <select id="review-user-id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select id="review-type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="App\Models\Tour">Tour</option>
                            <option value="App\Models\Place">Place</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Item</label>
                        <select id="review-item-id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Rating</label>
                        <select id="review-rating" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="1">1 - Poor</option>
                            <option value="2">2 - Fair</option>
                            <option value="3">3 - Good</option>
                            <option value="4">4 - Very Good</option>
                            <option value="5">5 - Excellent</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Comment</label>
                        <textarea id="review-comment" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></textarea>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Save</button>
                    <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let reviews = [];
    let users = [];
    let tours = [];
    let places = [];
    let editingId = null;

    async function loadReviews() {
        try {
            reviews = await apiRequest('/reviews');
            renderReviews();
        } catch (error) {
            document.getElementById('reviews-table-body').innerHTML = 
                '<tr><td colspan="5" class="px-6 py-4 text-center text-red-500">Error loading reviews</td></tr>';
        }
    }

    async function loadUsers() {
        try {
            users = await apiRequest('/users');
            const select = document.getElementById('review-user-id');
            select.innerHTML = users.map(u => `<option value="${u.id}">${u.name} (${u.email})</option>`).join('');
        } catch (error) {
            console.error('Error loading users:', error);
        }
    }

    async function loadTours() {
        try {
            tours = await apiRequest('/tours');
        } catch (error) {
            console.error('Error loading tours:', error);
        }
    }

    async function loadPlaces() {
        try {
            places = await apiRequest('/places');
        } catch (error) {
            console.error('Error loading places:', error);
        }
    }

    function updateReviewItems() {
        const type = document.getElementById('review-type').value;
        const select = document.getElementById('review-item-id');
        
        if (type === 'App\Models\Tour') {
            select.innerHTML = tours.map(t => `<option value="${t.id}">${t.title}</option>`).join('');
        } else {
            select.innerHTML = places.map(p => `<option value="${p.id}">${p.name} (${p.type})</option>`).join('');
        }
    }

    function renderReviews() {
        const tbody = document.getElementById('reviews-table-body');
        if (reviews.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No reviews found</td></tr>';
            return;
        }
        
        tbody.innerHTML = reviews.map(review => {
            const itemName = review.reviewable ? (review.reviewable.title || review.reviewable.name) : 'N/A';
            const stars = '⭐'.repeat(review.rating);
            return `
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">${review.user ? review.user.name : 'N/A'}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${itemName}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${stars} (${review.rating}/5)</td>
                    <td class="px-3 py-4 text-sm text-gray-500">${review.comment || '-'}</td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <button onclick="editReview(${review.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <button onclick="deleteReview(${review.id})" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function openCreateModal() {
        editingId = null;
        document.getElementById('modal-title').textContent = 'Add Review';
        document.getElementById('review-form').reset();
        document.getElementById('review-id').value = '';
        updateReviewItems();
        document.getElementById('review-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('review-modal').classList.add('hidden');
    }

    async function editReview(id) {
        const review = reviews.find(r => r.id === id);
        if (!review) return;
        
        editingId = id;
        document.getElementById('modal-title').textContent = 'Edit Review';
        document.getElementById('review-id').value = review.id;
        document.getElementById('review-user-id').value = review.user_id;
        document.getElementById('review-type').value = review.reviewable_type;
        updateReviewItems();
        setTimeout(() => {
            document.getElementById('review-item-id').value = review.reviewable_id;
        }, 100);
        document.getElementById('review-rating').value = review.rating;
        document.getElementById('review-comment').value = review.comment || '';
        document.getElementById('review-modal').classList.remove('hidden');
    }

    async function saveReview(event) {
        event.preventDefault();
        const formData = {
            user_id: parseInt(document.getElementById('review-user-id').value),
            reviewable_type: document.getElementById('review-type').value,
            reviewable_id: parseInt(document.getElementById('review-item-id').value),
            rating: parseInt(document.getElementById('review-rating').value),
            comment: document.getElementById('review-comment').value,
        };

        try {
            if (editingId) {
                await apiRequest(`/reviews/${editingId}`, 'PUT', formData);
            } else {
                await apiRequest('/reviews', 'POST', formData);
            }
            closeModal();
            loadReviews();
        } catch (error) {
            alert('Error saving review: ' + error.message);
        }
    }

    async function deleteReview(id) {
        if (!confirm('Are you sure you want to delete this review?')) return;
        
        try {
            await apiRequest(`/reviews/${id}`, 'DELETE');
            loadReviews();
        } catch (error) {
            alert('Error deleting review: ' + error.message);
        }
    }

    document.getElementById('review-type').addEventListener('change', updateReviewItems);
    
    Promise.all([loadUsers(), loadTours(), loadPlaces()]).then(() => {
        loadReviews();
    });
</script>
@endpush

