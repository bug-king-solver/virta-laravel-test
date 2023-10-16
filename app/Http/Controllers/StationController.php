<?php

namespace App\Http\Controllers;

use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/station",
     *     summary="Get a list of charging stations",
     *     description="Returns a list of all charging stations.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                     @OA\Property(property="latitude", type="number", format="float"),
     *                     @OA\Property(property="longitude", type="number", format="float"),
     *                     @OA\Property(property="company_id", type="integer"),
     *                     @OA\Property(property="address", type="string"),
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $stations = Station::all();
        return response()->json($stations, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/api/station",
     *     summary="Create a new charging station",
     *     description="Create a new charging station record.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="latitude", type="number", format="float"),
     *                 @OA\Property(property="longitude", type="number", format="float"),
     *                 @OA\Property(property="company_id", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Station created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="latitude", type="number", format="float"),
     *                 @OA\Property(property="longitude", type="number", format="float"),
     *                 @OA\Property(property="company_id", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'company_id' => 'required|integer',
            'address' => 'required|string',
        ]);

        try {
            $station = Station::create($request->all());
            return response()->json($station, 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to create the station.'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/station/{station}",
     *     summary="Get a specific charging station",
     *     description="Returns details of a specific charging station.",
     *     @OA\Parameter(
     *         name="station",
     *         in="path",
     *         description="ID of the station to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="latitude", type="number", format="float"),
     *                 @OA\Property(property="longitude", type="number", format="float"),
     *                 @OA\Property(property="company_id", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Station not found"
     *     )
     * )
     */
    public function show(Station $station)
    {
        if ($station) {
            return response()->json($station, 200);
        } else {
            return response()->json(['error' => 'Station not found.'], 404);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete(
     *     path="/api/station/{station}",
     *     summary="Delete a charging station",
     *     description="Delete a charging station record.",
     *     @OA\Parameter(
     *         name="station",
     *         in="path",
     *         description="ID of the station to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Station deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Station not found"
     *     )
     * )
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     *
     * @OA\Put(
     *     path="/api/station/{station}",
     *     summary="Update a charging station",
     *     description="Update a charging station record.",
     *     @OA\Parameter(
     *         name="station",
     *         in="path",
     *         description="ID of the station to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="latitude", type="number", format="float"),
     *                 @OA\Property(property="longitude", type="number", format="float"),
     *                 @OA\Property(property="company_id", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Station updated successfully",
     *         @OA\JsonContent(type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *                 @OA\Property(property="latitude", type="number", format="float"),
     *                 @OA\Property(property="longitude", type="number", format="float"),
     *                 @OA\Property(property="company_id", type="integer"),
     *                 @OA\Property(property="address", type="string"),
     *             })
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
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

        try {
            $station->update($request->all());
            return response()->json($station, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to update the station.'], 400);
        }
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Station  $station
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete(
     *     path="/api/station/{station}",
     *     summary="Delete a charging station",
     *     description="Delete a charging station record.",
     *     @OA\Parameter(
     *         name="station",
     *         in="path",
     *         description="ID of the station to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Station deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Station not found"
     *     )
     * )
     */
    public function destroy(Station $station)
    {
        try {
            $station->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to delete the station.'], 400);
        }
    }
    /**
     * Get charging stations within a specified radius.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/stations-within-radius",
     *     summary="Get charging stations within a radius",
     *     description="Returns a list of charging stations within a specified radius.",
     *     @OA\Parameter(
     *         name="latitude",
     *         in="query",
     *         description="Latitude",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="longitude",
     *         in="query",
     *         description="Longitude",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Parameter(
     *         name="radius",
     *         in="query",
     *         description="Radius (in kilometers)",
     *         required=true,
     *         @OA\Schema(type="number", format="float")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object")
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function stationsWithinRadius(Request $request)
    {
        $latitude = $request->query('latitude');
        $longitude = $request->query('longitude');
        $radius = $request->query('radius');

        try {
            $stations = DB::table('stations')
                ->selectRaw('stations.*, 
                (6371 * acos(cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) + sin(radians(?)) * sin(radians(latitude)))) AS distance',
                    [$latitude, $longitude, $latitude]
                )
                ->having('distance', '<=', $radius)
                ->orderBy('distance')
                ->get();

            // Group stations by location (latitude and longitude)
            $groupedStations = $stations->groupBy(['latitude', 'longitude']);
            return response()->json($groupedStations, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to retrieve stations within the specified radius.'], 400);
        }
    }
}