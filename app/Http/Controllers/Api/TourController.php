<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tour;

class TourController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tours = Tour::with('place')->get();
        return response()->json($tours);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'place_id' => 'nullable|exists:places,id',
            'price' => 'required|numeric|min:0',
            'duration_hours' => 'nullable|integer|min:1',
        ]);

        $tour = Tour::create($request->all());
        $tour->load('place');
        return response()->json($tour, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $tour = Tour::with('place')->find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }
        return response()->json($tour);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'place_id' => 'nullable|exists:places,id',
            'price' => 'sometimes|required|numeric|min:0',
            'duration_hours' => 'nullable|integer|min:1',
        ]);

        $tour->update($request->all());
        $tour->load('place');
        return response()->json($tour);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $tour = Tour::find($id);
        if (!$tour) {
            return response()->json(['message' => 'Tour not found'], 404);
        }

        $tour->delete();
        return response()->json(['message' => 'Tour deleted successfully']);
    }
}
