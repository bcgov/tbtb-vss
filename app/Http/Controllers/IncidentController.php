<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseEditRequest;
use App\Http\Requests\CaseStoreRequest;
use App\Models\AreaOfAudit;
use App\Models\CaseAuditType;
use App\Models\CaseNatureOffence;
use App\Models\CaseSanctionType;
use App\Models\Institution;
use App\Models\NatureOffence;
use App\Models\ReferralSource;
use App\Models\SanctionType;
use App\Models\User;
use Response;
use Inertia\Inertia;
use App\Models\Incident;

use App\Models\CaseFunding;
use App\Models\FundingType;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Requests\AjaxRequest;
use Illuminate\Support\Facades\Auth;

class IncidentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function dashboard()
    {
        $cases = Incident::isActive()->with('institution')
            ->where('bring_forward', true)
            ->where('auditor_user_id', Auth::user()->user_id)
            ->orderBy('created_at', 'desc')->paginate(25);

        return Inertia::render('Dashboard', ['status' => true, 'results' => $cases]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function index()
    {
        $cases = Incident::isActive()->with('institution')->orderBy('created_at', 'desc')->paginate(25);

        return Inertia::render('Cases', ['status' => true, 'results' => $cases]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function archived(Request $request)
    {
        $cases = Incident::archived()->with('institution')->orderBy('created_at', 'desc')->paginate(25);

        return inertia('Cases', ['status' => true, 'results' => $cases]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function create()
    {
        $areaOfAudits = AreaOfAudit::get();
        $natureOffences = NatureOffence::get();
        $referrals = ReferralSource::get();
        $sanctions = SanctionType::get();
        $staff = User::get();
        $schools = Institution::get();

        return inertia('CaseNew', ['status' => true,
            'areaOfAudits' => $areaOfAudits,
            'natureOffences' => $natureOffences,
            'referrals' => $referrals,
            'sanctions' => $sanctions,
            'staff' => $staff,
            'schools' => $schools,
            'now' => date('Y-m-d')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\CaseStoreRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CaseStoreRequest $request)
    {

        $case = Incident::create($request->validated());

        foreach ($request->new_sanction_codes as $key => $value){
            $sanction = SanctionType::where('sanction_code', $value)->first();
            CaseSanctionType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'sanction_code' => $sanction->sanction_code
            ]);
        }

        foreach ($request->new_offence_codes as $key => $value){
            $nature = NatureOffence::where('nature_code', $value)->first();
            CaseNatureOffence::firstOrCreate([
                'incident_id' => $case->incident_id,
                'nature_code' => $nature->nature_code
            ]);
        }

        foreach ($request->new_audit_codes as $row){
            $audit = AreaOfAudit::where('area_of_audit_code', $row['area_of_audit_code'])->first();
            CaseAuditType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'area_of_audit_code' => $audit->area_of_audit_code,
                'audit_type' => $row['audit_type']
            ]);
        }

//        return Redirect::route('cases.edit', [$case->id]);
        return Redirect::route('cases.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Incident  $case_funding
     * @return \Illuminate\Http\Response
     */
    public function show(Incident $case_funding)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Incident  $case
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function edit(Incident $case)
    {
        $case = Incident::where('id', $case->id)->with('audits', 'offences.offence', 'sanctions.sanction', 'institution')->first();
        $areaOfAudits = AreaOfAudit::get();
        $natureOffences = NatureOffence::get();
        $referrals = ReferralSource::get();
        $sanctions = SanctionType::get();
        $staff = User::get();
        $schools = Institution::get();

        return inertia('CaseEdit', ['status' => true, 'result' => $case,
            'areaOfAudits' => $areaOfAudits,
            'natureOffences' => $natureOffences,
            'referrals' => $referrals,
            'sanctions' => $sanctions,
            'staff' => $staff,
            'schools' => $schools,
            'now' => date('Y-m-d')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\CaseEditRequest  $request
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function update(CaseEditRequest $request, Incident $case)
    {

        $case->update($request->validated());

        foreach ($request->new_sanction_codes as $key => $value){
            $sanction = SanctionType::where('sanction_code', $value)->first();
            CaseSanctionType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'sanction_code' => $sanction->sanction_code
            ]);
        }

        foreach ($request->new_offence_codes as $key => $value){
            $nature = NatureOffence::where('nature_code', $value)->first();
            CaseNatureOffence::firstOrCreate([
                'incident_id' => $case->incident_id,
                'nature_code' => $nature->nature_code
            ]);
        }

        foreach ($request->new_audit_codes as $row){
            $audit = AreaOfAudit::where('area_of_audit_code', $row['area_of_audit_code'])->first();
            CaseAuditType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'area_of_audit_code' => $audit->area_of_audit_code,
                'audit_type' => $row['audit_type']
            ]);
        }

        return Redirect::route('cases.edit', [$case->id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CaseFunding  $case_funding
     * @return \Illuminate\Http\Response
     */
    public function destroy(CaseFunding $case_funding)
    {
    }





    public function sinSearch(AjaxRequest $request)
    {
        $cases = Incident::where('sin', $request->input('inputSin'))->with('institution')->get();
        return inertia('SearchResults', ['status' => true, 'results' => $cases, 'sin' => $request->input('inputSin')]);
    }

    public function nameSearch(AjaxRequest $request)
    {
        $valid = false;
        $cases = Incident::with('institution');
        if(!is_null($request->inputFirstName)){
            $cases = $cases->where('first_name', $request->inputFirstName);
            $valid = true;
        }
        if(!is_null($request->inputLastName)){
            $cases = $cases->where('last_name', $request->inputLastName);
            $valid = true;
        }

        if($valid){
            $cases = $cases->get();
        }else{
            $cases = null;
        }
        return inertia('SearchResults', ['status' => true, 'results' => $cases]);
    }

    public function activeUserSearch(AjaxRequest $request)
    {
        $cases = Incident::where('auditor_user_id', $request->selectActiveUser)
            ->orWhere('investigator_user_id', $request->selectActiveUser)
            ->with('institution')
            ->get();
        return inertia('SearchResults', ['status' => true, 'results' => $cases]);
    }

    public function cancelledUserSearch(AjaxRequest $request)
    {
        $cases = Incident::where('auditor_user_id', $request->selectCancelledUser)
            ->orWhere('investigator_user_id', $request->selectCancelledUser)
            ->with('institution')
            ->get();
        return inertia('SearchResults', ['status' => true, 'results' => $cases]);
    }

}
