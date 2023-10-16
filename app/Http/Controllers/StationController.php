<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $stations = Station::all();
        return response()->json($stations);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $station = Station::create($request->all());
            return response()->json($station, 201);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Station $station)
    {
        return response()->json($station);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Station $station)
    {
        $request->validate([
            'name' => 'required|string|min:5',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'company_id' => 'required|exists:companies,id',
            'address' => 'required|string|min:5',
        ], [
            'name.required' => 'This name is required',
            'latitude.required' => 'This latitude is required',
            'longitude.required' => 'This longitude is required',
            'company_id.required' => 'This company_id is required',
            'address.required' => 'This address is required',
        ]);

        $station->name = $request->name;
        $station->latitude = $request->latitude;
        $station->longitude = $request->longitude;
        $station->company_id = $request->company_id;
        $station->address = $request->address;
        $station->save();
        return response()->json($station, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Station::findOrFail($id)->delete();
        return response()->json(null, 204);
    }
}