<?php

namespace App\Http\Controllers;

use App\Models\CaseFunding;
use App\Models\CaseSanctionType;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CaseSanctionTypeController extends Controller
{

    public function deleteSanction(Request $request, Incident $case)
    {
        $sanction = CaseSanctionType::where('incident_id', $case->incident_id)->where('sanction_code', $request->sanction_code)->delete();
        return Redirect::route('cases.edit', [$case->id]);

    }
}
