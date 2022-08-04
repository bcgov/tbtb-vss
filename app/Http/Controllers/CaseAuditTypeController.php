<?php

namespace App\Http\Controllers;

use App\Models\CaseAuditType;
use App\Models\Incident;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class CaseAuditTypeController extends Controller
{
    public function deleteAuditType(Request $request, Incident $case)
    {
        CaseAuditType::where('incident_id', $case->incident_id)
            ->where('area_of_audit_code', $request->area_of_audit_code)
            ->where('audit_type', $request->audit_type)
            ->delete();

        return Redirect::route('cases.edit', [$case->id]);
    }
}
