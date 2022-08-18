<?php

namespace App\Http\Controllers;

use App\Http\Requests\AreaOfAuditStoreRequest;
use App\Models\AreaOfAudit;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class AreaOfAuditController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $areas = AreaOfAudit::orderBy('area_of_audit_code', 'asc')->get();

        return Inertia::render('Maintenance', ['status' => true, 'results' => $areas, 'page' => 'area-of-audit']);
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
     * @param  \App\Http\Requests\AreaOfAuditStoreRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(AreaOfAuditStoreRequest $request)
    {
        AreaOfAudit::create($request->validated());

        return Redirect::route('maintenance.area-of-audit.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NatureOffence  $natureOffence
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
     * @param  \App\Http\Requests\AreaOfAuditStoreRequest  $request  $request
     * @param  \App\Models\AreaOfAudit  $areaOfAudit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AreaOfAuditStoreRequest $request, AreaOfAudit $areaOfAudit)
    {
        $new_area = AreaOfAudit::create($request->validated());
        $areaOfAudit->incidents()->update(['area_of_audit_code' => $new_area->area_of_audit_code]);
        $areaOfAudit->caseAuditTypes()->update(['area_of_audit_code' => $new_area->area_of_audit_code]);

        $areaOfAudit->delete();

        return Redirect::route('maintenance.area-of-audit.index');
    }
}
