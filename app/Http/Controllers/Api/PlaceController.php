<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Place;

class PlaceController extends Controller
{
    // GET /api/places
    public function index()
    {
        $places = Place::with(['tours', 'reviews'])->get();
        return response()->json($places);
    }

    // GET /api/places/{id}
    public function show($id)
    {
        $place = Place::with(['tours', 'reviews'])->find($id);
        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }
        return response()->json($place);
    }

    // POST /api/places
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $place = Place::create($request->all());
        return response()->json($place, 201);
    }

    // PUT /api/places/{id}
    public function update(Request $request, $id)
    {
        $place = Place::find($id);
        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $place->update($request->all());
        return response()->json($place);
    }

    // DELETE /api/places/{id}
    public function destroy($id)
    {
        $place = Place::find($id);
        if (!$place) {
            return response()->json(['message' => 'Place not found'], 404);
        }

        $place->delete();
        return response()->json(['message' => 'Place deleted']);
    }

}