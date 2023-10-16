<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Station;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

/**
 * @OA\Info(
 *     title="Virta API",
 *     version="1.0",
 *     description="API for managing companies and their stations.",
 *     @OA\Contact(
 *         email="michaelgovern0826@gmail.com",
 *         name="Michael Govern"
 *     )
 * )
 */
class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/company",
     *     summary="Get a list of companies",
     *     description="Returns a list of all companies.",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 properties={
     *                     @OA\Property(property="id", type="integer"),
     *                     @OA\Property(property="parent_company_id", type="integer"),
     *                     @OA\Property(property="name", type="string"),
     *                 }
     *             )
     *         )
     *     )
     * )
     */
    public function index()
    {
        $companies = Company::all();
        return response()->json($companies, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     *
     * @OA\Post(
     *     path="/api/company",
     *     summary="Create a new company",
     *     description="Create a new company record.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="parent_company_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Company created successfully",
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="parent_company_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
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
            'parent_company_id' => 'nullable|integer',
            'name' => 'required|string|min:5',
        ], [
            'name.required' => 'The name field is required.',
        ]);

        try {
            $company = Company::create($request->all());
            return response()->json($company, 201);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to create the company.'], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/company/{company}",
     *     summary="Get a specific company",
     *     description="Returns details of a specific company.",
     *     @OA\Parameter(
     *         name="company",
     *         in="path",
     *         description="ID of the company to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *         @OA\JsonContent(type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="parent_company_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *             })
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function show(Company $company)
    {
        if ($company) {
            return response()->json($company, 200);
        } else {
            return response()->json(['error' => 'Company not found.'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     *
     * @OA\Put(
     *     path="/api/company/{company}",
     *     summary="Update a company",
     *     description="Update a company record.",
     *     @OA\Parameter(
     *         name="company",
     *         in="path",
     *         description="ID of the company to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             properties={
     *                 @OA\Property(property="name", type="string"),
     *             }
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Company updated successfully",
     *         @OA\JsonContent(type="object",
     *             properties={
     *                 @OA\Property(property="id", type="integer"),
     *                 @OA\Property(property="parent_company_id", type="integer"),
     *                 @OA\Property(property="name", type="string"),
     *             })
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|min:5',
        ], [
            'name.required' => 'The name field is required.',
        ]);

        try {
            $company->update($request->all());
            return response()->json($company, 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to update the company.'], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Http\Response
     *
     * @OA\Delete(
     *     path="/api/company/{company}",
     *     summary="Delete a company",
     *     description="Delete a company record.",
     *     @OA\Parameter(
     *         name="company",
     *         in="path",
     *         description="ID of the company to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Company deleted successfully"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function destroy(Company $company)
    {
        try {
            $company->delete();
            return response()->json(null, 204);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Failed to delete the company.'], 400);
        }
    }

    /**
     * Get stations owned by a company and its children.
     *
     * @param  int  $company_id
     * @return \Illuminate\Http\Response
     *
     * @OA\Get(
     *     path="/api/child-stations/{company_id}",
     *     summary="Get stations owned by a company and its children",
     *     description="Returns a list of stations owned by a company and its children.",
     *     @OA\Parameter(
     *         name="company_id",
     *         in="path",
     *         description="ID of the company to retrieve stations for",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
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
     *                 })
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Company not found"
     *     )
     * )
     */
    public function childStations($company_id)
    {
        $company = Company::findOrFail($company_id);
        if ($company) {
            // Get all child companies recursively
            $childCompanies = $company->getChildCompanies();
            // Get all stations owned by child companies and the current company
            $stations = Station::whereIn('company_id', $childCompanies->pluck('id')->push($company_id))->get();

            return response()->json($stations, 200);
        } else {
            return response()->json(['error' => 'Company not found.'], 404);
        }
    }
}