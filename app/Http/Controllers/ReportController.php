<?php

namespace App\Http\Controllers;

use App\Models\AreaOfAudit;
use App\Models\CaseFunding;
use App\Models\FundingType;
use App\Models\Incident;
use Illuminate\Http\Request;
use Inertia\Inertia;
use PDF;

class ReportController extends Controller
{
    public function downloadSingleStudentReport(Request $request, Incident $case)
    {
        $case = Incident::where('id', $case->id)->with(
            'primaryAudit', 'referral',
            'funds.fundingType', 'comments', 'institution', 'audits', 'offences.offence', 'sanctions')->first();
        $now_d = date('Y-m-d');
        $now_t = date('H:m:i');
        $pdf = PDF::loadView('pdf', compact('case', 'now_d', 'now_t'));

        return $pdf->download(mt_rand() . '-' . $case->incident_id . '-student_report.pdf');
    }

    public function showOverAward(Request $request)
    {
        $table = [];
        $funding_types = FundingType::orderBy('funding_type')->get();
        $areas_of_audit = AreaOfAudit::orderBy('description')->get();
        foreach ($areas_of_audit as $area){
            $table[$area->description] = [];
            $table[$area->description]['TOTAL'] = 0;
            foreach ($funding_types as $type){
                $table[$area->description][$type->funding_type] = 0;
            }
        }
        $pre_audit_table = $table;
        $post_audit_table = $table;
        $total_audit_table = $table;

        $funds = CaseFunding::with('incident.primaryAudit')
            ->where('over_award', '>', 0)
            ->limit(1000)->get();


        foreach ($funds as $fund){
            if($fund->incident->audit_type == 'P') {
                $post_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
                $post_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
            }
            else {
                $pre_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
                $pre_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);
            }
            $total_audit_table[$fund->incident->primaryAudit->description][$fund->funding_type] += floatval($fund->over_award);
            $total_audit_table[$fund->incident->primaryAudit->description]['TOTAL'] += floatval($fund->over_award);

        }

echo "<table><tr><td>PRE AUDIT<br/><pre>";
        print_r($pre_audit_table);
        echo "</pre></td><td>POST AUDIT<br/><pre>";

        print_r($post_audit_table);
        echo "</pre></td><td>TOTAL AUDIT<br/><pre>";
        print_r($total_audit_table);
        echo "</pre></td></tr></table>";
   }

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
