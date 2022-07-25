<?php

namespace App\Http\Controllers;

use App\Models\CaseNatureOffence;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CaseNatureOffenceController extends Controller
{
    public function deleteOffence(Request $request, Incident $case)
    {
        $offence = CaseNatureOffence::where('incident_id', $case->incident_id)->where('nature_code', $request->nature_code)->delete();
        return Redirect::route('cases.edit', [$case->id]);

    }
}
