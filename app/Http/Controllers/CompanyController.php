<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Station;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $companies = Company::all();
        return response()->json($companies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $company = Company::create($request->all());
            $company->parent_company_id = $request->parentId;
            return response()->json($company, 201);
        } catch (\Throwable $th) {
            return response()->json($th);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Company $company)
    {
        return response()->json($company);
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
    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|min:5',
        ], [
            'name.required' => 'This name is required',
        ]);

        $company->name = $request->name;
        $company->parent_company_id = $request->parentId;
        $company->save();
        return response()->json($company, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Company::findOrFail($id)->delete();
        return response()->json(null, 204);
    }

    public function childStations($company_id)
    {
        $company = Company::findOrFail($company_id);
        // Get all child companies recursively
        $childCompanies = $company->getChildCompanies();
        // Get all stations owned by child companies and the current company
        $stations = Station::whereIn('company_id', $childCompanies->pluck('id')->push($company_id))->get();

        return response()->json($stations);
    }
}