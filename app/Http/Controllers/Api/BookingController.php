<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::with(['user', 'bookable'])->get();
        return response()->json($bookings);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'bookable_type' => 'required|string|in:App\Models\Tour,App\Models\Place',
            'bookable_id' => 'required|integer',
            'date' => 'nullable|date',
            'guests' => 'nullable|integer|min:1',
            'total_price' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:pending,confirmed,cancelled',
        ]);

        $booking = Booking::create($request->all());
        $booking->load(['user', 'bookable']);
        return response()->json($booking, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $booking = Booking::with(['user', 'bookable'])->find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }
        return response()->json($booking);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'bookable_type' => 'sometimes|required|string|in:App\Models\Tour,App\Models\Place',
            'bookable_id' => 'sometimes|required|integer',
            'date' => 'nullable|date',
            'guests' => 'nullable|integer|min:1',
            'total_price' => 'sometimes|required|numeric|min:0',
            'status' => 'nullable|string|in:pending,confirmed,cancelled',
        ]);

        $booking->update($request->all());
        $booking->load(['user', 'bookable']);
        return response()->json($booking);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $booking = Booking::find($id);
        if (!$booking) {
            return response()->json(['message' => 'Booking not found'], 404);
        }

        $booking->delete();
        return response()->json(['message' => 'Booking deleted successfully']);
    }
}
