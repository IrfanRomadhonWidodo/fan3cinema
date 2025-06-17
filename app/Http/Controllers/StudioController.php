<?php

// app/Http/Controllers/StudioController.php

namespace App\Http\Controllers;

use App\Models\Studio;
use Illuminate\Http\Request;

class StudioController extends Controller
{
    public function index()
    {
        $studios = Studio::all();
        return response()->json($studios);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_studio' => 'required|string',
            'kapasitas' => 'required|integer',
        ]);

        $studio = Studio::create($request->all());
        return response()->json($studio, 201);
    }

    public function show($id)
    {
        return Studio::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $studio = Studio::findOrFail($id);
        $studio->update($request->all());
        return response()->json($studio);
    }

    public function destroy($id)
    {
        $studio = Studio::findOrFail($id);
        $studio->delete();
        return response()->json(['message' => 'Studio deleted']);
    }
}
