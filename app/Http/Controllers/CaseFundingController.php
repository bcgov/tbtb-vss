<?php

namespace App\Http\Controllers;

use App\Models\CaseFunding;
use App\Models\FundingType;
use App\Models\Incident;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CaseFundingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
     * @param  \App\Models\Incident  $caseFunding
     * @return \Illuminate\Http\Response
     */
    public function show(Incident $caseFunding)
    {
        $case = Incident::where('id', $caseFunding->id)->with('funds.fundingType', 'institution')->first();
        $funds = FundingType::get();
        $schools = Institution::get();

        return inertia('CaseFunding', ['status' => true, 'result' => $case, 'funds' => $funds, 'schools' => $schools, 'now' => date('Y-m-d')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CaseFunding  $caseFunding
     * @return \Illuminate\Http\Response
     */
    public function edit(CaseFunding $caseFunding)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Incident  $caseFunding
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Incident $caseFunding)
    {
        foreach ($request->old_rows as $row) {
            CaseFunding::where('id', $row['id'])
                ->update([
                    'application_number' => $row['application_number'],
                    'funding_type' => $row['funding_type']['funding_type'],
                    'fund_entry_date' => $row['fund_entry_date'],
                    'over_award' => $row['over_award'],
                    'prevented_funding' => $row['prevented_funding'],
                ]);
        }

        foreach ($request->new_rows as $row) {
            CaseFunding::create([
                'incident_id' => $caseFunding->incident_id,
                'application_number' => $row['application_number'],
                'funding_type' => $row['funding_type'],
                'fund_entry_date' => $row['fund_entry_date'],
                'over_award' => $row['over_award'],
                'prevented_funding' => $row['prevented_funding'],
            ]);
        }

        $case = Incident::where('id', $caseFunding->id)->with('funds.fundingType', 'institution')->first();
        $funds = FundingType::get();
        $schools = Institution::get();

        return inertia('CaseFunding', ['status' => true, 'result' => $case, 'funds' => $funds, 'schools' => $schools, 'now' => date('Y-m-d')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CaseFunding  $caseFunding
     * @return \Illuminate\Http\Response
     */
    public function destroy(CaseFunding $caseFunding)
    {
        $incident_id = $caseFunding->incident_id;
        $caseFunding->deleted_by_user_id = Auth::user()->user_id;
        $caseFunding->save();

        $caseFunding->delete();

        $case = Incident::where('incident_id', $incident_id)->first();

        return Redirect::route('case-funding.show', [$case->id]);
    }
}
