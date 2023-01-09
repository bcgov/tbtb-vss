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
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\AreaOfAuditStoreRequest  $request  $request
     * @param  \App\Models\AreaOfAudit  $areaOfAudit
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(AreaOfAuditStoreRequest $request, AreaOfAudit $areaOfAudit)
    {
        //if the area code updated
        if ($request->area_of_audit_code !== $areaOfAudit->area_of_audit_code) {
            //create new area
            $new_area = AreaOfAudit::create($request->validated());

            //re-attach incidents from the old school to the new
            $areaOfAudit->incidents()->update(['area_of_audit_code' => $new_area->area_of_audit_code]);
            $areaOfAudit->caseAuditTypes()->update(['area_of_audit_code' => $new_area->area_of_audit_code]);

            //delete old school
            $areaOfAudit->delete();
        } else {
            AreaOfAudit::where('id', $areaOfAudit->id)->update($request->validated());
        }

        return Redirect::route('maintenance.area-of-audit.index');
    }
}
