<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaseStoreRequest;
use App\Models\AreaOfAudit;
use App\Models\CaseAuditType;
use App\Models\CaseNatureOffence;
use App\Models\CaseSanctionType;
use App\Models\Incident;
use App\Models\Institution;
use App\Models\NatureOffence;
use App\Models\ReferralSource;
use App\Models\SanctionType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class IncidentController extends Controller
{
    private function paginateCases($cases)
    {
        if (request()->filter_sin !== null) {
            $cases = $cases->where('sin', request()->filter_sin);
        }

        if (request()->filter_user !== null) {
            $cases = $cases->where('auditor_user_id', request()->filter_user)
                ->orWhere('investigator_user_id', request()->filter_user);
        }

        if (request()->filter_status !== null) {
            $cases = $cases->where('incident_status', request()->filter_status);
        }

        if (request()->filter_fname !== null) {
            $cases = $cases->where('first_name', 'ILIKE', '%'.request()->filter_fname.'%');
        }
        if (request()->filter_lname !== null) {
            $cases = $cases->where('last_name', 'ILIKE', '%'.request()->filter_lname.'%');
        }
        if (request()->filter_type !== null) {
            if (request()->filter_type === 'archive') {
                $cases = $cases->archived();
            } else {
                $cases = $cases->isActive();
            }
        } else {
            $cases = $cases->isActive();
        }

        if (request()->sort !== null) {
            $cases = $cases->orderBy(request()->sort, request()->direction);
        } else {
            $cases = $cases->orderBy('created_at', 'desc');
        }

        return $cases->with('institution')->paginate(25)->onEachSide(1)->appends(request()->query());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function dashboard()
    {
        $cases = Incident::where('bring_forward', true)->where('auditor_user_id', Auth::user()->user_id);
        $cases = $this->paginateCases($cases);

        return Inertia::render('Dashboard', ['status' => true, 'results' => $cases]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response::render
     */
    public function index()
    {
        $cases = new Incident();
        $cases = $this->paginateCases($cases);

        if (request()->filter_type !== null && request()->filter_type === 'archive') {
            return inertia('ArchiveCases', ['status' => true, 'results' => $cases]);
        }

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

        return inertia('ArchiveCases', ['status' => true, 'results' => $cases]);
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
            'now' => date('Y-m-d'), ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(CaseStoreRequest $request)
    {
        $case = Incident::create($request->validated());

        $this->addAttachedRecords($request, $case);

        return Redirect::route('cases.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function edit(Incident $case)
    {
        $case = Incident::where('id', $case->id)->with('audits', 'offences.offence', 'sanctions.sanction', 'institution')->withTrashed()->first();
        $areaOfAudits = AreaOfAudit::get();
        $natureOffences = NatureOffence::get();
        $referrals = ReferralSource::get();
        $sanctions = SanctionType::get();
        $staff = User::get();
        $schools = Institution::orderBy('institution_code')->get();

        return inertia('CaseEdit', ['status' => true, 'result' => $case,
            'areaOfAudits' => $areaOfAudits,
            'natureOffences' => $natureOffences,
            'referrals' => $referrals,
            'sanctions' => $sanctions,
            'staff' => $staff,
            'schools' => $schools,
            'now' => date('Y-m-d'), ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\Incident  $incident
     * @return \Illuminate\Http\Response
     */
    public function update(CaseStoreRequest $request, Incident $case)
    {
        $case->update($request->validated());

        $this->addAttachedRecords($request, $case);

        return Redirect::route('cases.edit', [$case->id]);
    }

    private function addAttachedRecords($request, $case)
    {
        $case->audits()->delete();
        foreach ($request->old_audit_codes as $row) {
            $audit = AreaOfAudit::where('area_of_audit_code', $row['area_of_audit_code'])->first();
            CaseAuditType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'area_of_audit_code' => $audit->area_of_audit_code,
                'audit_type' => $row['audit_type'],
            ]);
        }

        $case->offences()->delete();
        foreach ($request->old_offence_codes as $value) {
            $nature = NatureOffence::where('nature_code', $value)->first();
            CaseNatureOffence::firstOrCreate([
                'incident_id' => $case->incident_id,
                'nature_code' => $nature->nature_code,
            ]);
        }

        $case->sanctions()->delete();
        foreach ($request->old_sanction_codes as $value) {
            $sanction = SanctionType::where('sanction_code', $value)->first();
            CaseSanctionType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'sanction_code' => $sanction->sanction_code,
            ]);
        }

        foreach ($request->new_sanction_codes as $value) {
            $sanction = SanctionType::where('sanction_code', $value)->first();
            CaseSanctionType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'sanction_code' => $sanction->sanction_code,
            ]);
        }

        foreach ($request->new_offence_codes as $key => $value) {
            $nature = NatureOffence::where('nature_code', $value)->first();
            CaseNatureOffence::firstOrCreate([
                'incident_id' => $case->incident_id,
                'nature_code' => $nature->nature_code,
            ]);
        }

        foreach ($request->new_audit_codes as $row) {
            $audit = AreaOfAudit::where('area_of_audit_code', $row['area_of_audit_code'])->first();
            CaseAuditType::firstOrCreate([
                'incident_id' => $case->incident_id,
                'area_of_audit_code' => $audit->area_of_audit_code,
                'audit_type' => $row['audit_type'],
            ]);
        }
    }
}
