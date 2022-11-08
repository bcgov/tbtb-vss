<?php

namespace App\Http\Controllers;

use App\Http\Requests\NatureOffenceStoreRequest;
use App\Models\NatureOffence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class NatureOffenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $offences = NatureOffence::orderBy('nature_code', 'asc')->get();

        return Inertia::render('Maintenance', ['status' => true, 'results' => $offences, 'page' => 'nature-offence']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  NatureOffenceStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(NatureOffenceStoreRequest $request)
    {
        NatureOffence::create($request->validated());

        return Redirect::route('maintenance.nature-offence.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  NatureOffenceStoreRequest  $request
     * @param  \App\Models\NatureOffence  $natureOffence
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(NatureOffenceStoreRequest $request, NatureOffence $natureOffence)
    {
        //if the nature offence code updated
        if ($request->nature_code !== $natureOffence->nature_code) {
            //create new area
            $new_offence = NatureOffence::create($request->validated());

            //re-attach incidents from the old school to the new
            $natureOffence->offences()->update(['nature_code' => $new_offence->nature_code]);

            //delete old school
            $natureOffence->delete();
        } else {
            NatureOffence::where('id', $natureOffence->id)->update($request->validated());
        }

        return Redirect::route('maintenance.nature-offence.index');
    }

}
