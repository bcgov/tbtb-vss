<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstitutionStoreRequest;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class InstitutionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $schools = Institution::orderBy('institution_code', 'asc')->get();

        return Inertia::render('Maintenance', ['status' => true, 'results' => $schools, 'page' => 'school']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\InstitutionStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(InstitutionStoreRequest $request)
    {
        Institution::create($request->validated());

        return Redirect::route('maintenance.school.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function show(Institution $institution)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function edit(Institution $institution)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\InstitutionStoreRequest  $request
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(InstitutionStoreRequest $request, Institution $institution)
    {
        $new_school = Institution::create($request->validated());
        $institution->incidents()->update(['institution_code' => $new_school->institution_code]);

        $institution->delete();

        return Redirect::route('maintenance.school.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Institution  $institution
     * @return \Illuminate\Http\Response
     */
    public function destroy(Institution $institution)
    {
        //
    }
}
