@extends('layouts.app')

@section('title', 'Bookings')

@section('content')
<div class="px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2">Bookings</h1>
                <p class="text-lg text-gray-600">View and manage all bookings</p>
            </div>
            <button onclick="openCreateModal()" class="btn-primary">
                <i class="fas fa-plus mr-2"></i> Add New Booking
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
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Date</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Guests</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Total Price</th>
                                <th class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Status</th>
                                <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white" id="bookings-table-body">
                            <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="booking-modal" class="hidden fixed z-10 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="booking-form" onsubmit="saveBooking(event)">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">Add Booking</h3>
                    <input type="hidden" id="booking-id">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">User</label>
                        <select id="booking-user-id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Type</label>
                        <select id="booking-type" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="App\Models\Tour">Tour</option>
                            <option value="App\Models\Place">Place</option>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Item</label>
                        <select id="booking-item-id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm"></select>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Date</label>
                        <input type="date" id="booking-date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Guests</label>
                        <input type="number" id="booking-guests" value="1" min="1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Total Price</label>
                        <input type="number" step="0.01" id="booking-price" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <select id="booking-status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                            <option value="pending">Pending</option>
                            <option value="confirmed">Confirmed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
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
    let bookings = [];
    let users = [];
    let tours = [];
    let places = [];
    let editingId = null;

    async function loadBookings() {
        try {
            bookings = await apiRequest('/bookings');
            renderBookings();
        } catch (error) {
            document.getElementById('bookings-table-body').innerHTML = 
                '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Error loading bookings</td></tr>';
        }
    }

    async function loadUsers() {
        try {
            users = await apiRequest('/users');
            const select = document.getElementById('booking-user-id');
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

    function updateBookingItems() {
        const type = document.getElementById('booking-type').value;
        const select = document.getElementById('booking-item-id');
        
        if (type === 'App\Models\Tour') {
            select.innerHTML = tours.map(t => `<option value="${t.id}">${t.title} - $${parseFloat(t.price).toFixed(2)}</option>`).join('');
        } else {
            select.innerHTML = places.map(p => `<option value="${p.id}">${p.name} (${p.type})</option>`).join('');
        }
    }

    function renderBookings() {
        const tbody = document.getElementById('bookings-table-body');
        if (bookings.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No bookings found</td></tr>';
            return;
        }
        
        tbody.innerHTML = bookings.map(booking => {
            const itemName = booking.bookable ? (booking.bookable.title || booking.bookable.name) : 'N/A';
            return `
                <tr>
                    <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">${booking.user ? booking.user.name : 'N/A'}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${itemName}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${booking.date || '-'}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">${booking.guests || 1}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">$${parseFloat(booking.total_price).toFixed(2)}</td>
                    <td class="whitespace-nowrap px-3 py-4 text-sm">
                        <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusColor(booking.status)}">${booking.status}</span>
                    </td>
                    <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                        <button onclick="editBooking(${booking.id})" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                        <button onclick="deleteBooking(${booking.id})" class="text-red-600 hover:text-red-900">Delete</button>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function getStatusColor(status) {
        const colors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-green-100 text-green-800',
            'cancelled': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    function openCreateModal() {
        editingId = null;
        document.getElementById('modal-title').textContent = 'Add Booking';
        document.getElementById('booking-form').reset();
        document.getElementById('booking-id').value = '';
        document.getElementById('booking-status').value = 'pending';
        updateBookingItems();
        document.getElementById('booking-modal').classList.remove('hidden');
    }

    function closeModal() {
        document.getElementById('booking-modal').classList.add('hidden');
    }

    async function editBooking(id) {
        const booking = bookings.find(b => b.id === id);
        if (!booking) return;
        
        editingId = id;
        document.getElementById('modal-title').textContent = 'Edit Booking';
        document.getElementById('booking-id').value = booking.id;
        document.getElementById('booking-user-id').value = booking.user_id;
        document.getElementById('booking-type').value = booking.bookable_type;
        updateBookingItems();
        setTimeout(() => {
            document.getElementById('booking-item-id').value = booking.bookable_id;
        }, 100);
        document.getElementById('booking-date').value = booking.date || '';
        document.getElementById('booking-guests').value = booking.guests || 1;
        document.getElementById('booking-price').value = booking.total_price;
        document.getElementById('booking-status').value = booking.status;
        document.getElementById('booking-modal').classList.remove('hidden');
    }

    async function saveBooking(event) {
        event.preventDefault();
        const formData = {
            user_id: parseInt(document.getElementById('booking-user-id').value),
            bookable_type: document.getElementById('booking-type').value,
            bookable_id: parseInt(document.getElementById('booking-item-id').value),
            date: document.getElementById('booking-date').value || null,
            guests: parseInt(document.getElementById('booking-guests').value) || 1,
            total_price: parseFloat(document.getElementById('booking-price').value),
            status: document.getElementById('booking-status').value,
        };

        try {
            if (editingId) {
                await apiRequest(`/bookings/${editingId}`, 'PUT', formData);
            } else {
                await apiRequest('/bookings', 'POST', formData);
            }
            closeModal();
            loadBookings();
        } catch (error) {
            alert('Error saving booking: ' + error.message);
        }
    }

    async function deleteBooking(id) {
        if (!confirm('Are you sure you want to delete this booking?')) return;
        
        try {
            await apiRequest(`/bookings/${id}`, 'DELETE');
            loadBookings();
        } catch (error) {
            alert('Error deleting booking: ' + error.message);
        }
    }

    document.getElementById('booking-type').addEventListener('change', updateBookingItems);
    
    Promise.all([loadUsers(), loadTours(), loadPlaces()]).then(() => {
        loadBookings();
    });
</script>
@endpush

