<?php

namespace App\Http\Controllers;

use App\Models\AreaOfAudit;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function index()
    {
        return Inertia::render('Dashboard');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function goToPage(Request $request, $page = 'areas-of-audit')
    {
        return Inertia::render('Maintenance', ['page' => $page]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\AreaOfAudit  $areaOfAudit
     * @return \Illuminate\Http\Response
     */
    public function show(AreaOfAudit $areaOfAudit)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\AreaOfAudit  $areaOfAudit
     * @return \Illuminate\Http\Response
     */
    public function edit(AreaOfAudit $areaOfAudit)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\AreaOfAudit  $areaOfAudit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, AreaOfAudit $areaOfAudit)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\AreaOfAudit  $areaOfAudit
     * @return \Illuminate\Http\Response
     */
    public function destroy(AreaOfAudit $areaOfAudit)
    {
        //
    }
}
