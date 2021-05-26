<?php

namespace App\Http\Controllers;

use App\Http\Requests\Company\StoreCompanyRequest;
use App\Http\Requests\Company\UpdateCompanyRequest;
use App\Models\Company;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = Company::with('users')->get();
        return response($resources->toJSON(),200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreCompanyRequest $request
     * @param Company $company
     * @return void
     */
    public function store(StoreCompanyRequest $request, Company $company)
    {
        $resource = $company->create($request->all());
        return $this->show($resource->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     */
    public function show($id)
    {
        $resource = Company::with('users')->findOrFail($id);
        return response($resource->toJSON(),200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateCompanyRequest $request
     * @param int $id
     * @return void
     */
    public function update(UpdateCompanyRequest $request, $id)
    {
        $resource = Company::findOrFail($id);
        $resource->fill($request->all())->save();
        return $this->show($resource->id);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     */
    public function destroy($id)
    {
        $resource = Company::findOrFail($id);
        $resource->delete();
        return response([],200);

    }
}
