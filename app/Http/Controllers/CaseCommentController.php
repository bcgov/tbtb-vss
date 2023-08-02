<?php

namespace App\Http\Controllers;

use App\Models\CaseComment;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class CaseCommentController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function show(Incident $caseComment)
    {
        $case = Incident::where('id', $caseComment->id)->with('comments', 'institution')->first();
        $staff = User::where('disabled', false)->where('end_date', null)->get();

        return inertia('CaseComment', ['status' => true, 'result' => $case, 'staff' => $staff, 'now' => date('Y-m-d')]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Inertia\ResponseFactory|\Inertia\Response
     */
    public function update(Request $request, Incident $caseComment)
    {
        $case = Incident::where('id', $caseComment->id)->with('comments', 'institution')->first();
        $staff = User::where('disabled', false)->where('end_date', null)->get();

        $current_user_id = Auth::user()->user_id;
        foreach ($request->old_rows as $row) {
            if ($row['staff_user_id'] == $current_user_id) {
                CaseComment::where('id', $row['id'])
                    ->update(['comment_text' => $row['comment_text']]);
            }
        }

        foreach ($request->new_rows as $row) {
            CaseComment::create([
                'incident_id' => $caseComment->incident_id,
                'staff_user_id' => $current_user_id,
                'comment_date' => date('Y-m-d'),
                'comment_text' => trim($row['comment_text']),
            ]);
        }

        return inertia('CaseComment', ['status' => true, 'result' => $case, 'staff' => $staff, 'now' => date('Y-m-d')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(CaseComment $caseComment)
    {
        $incident_id = $caseComment->incident_id;
        $caseComment->deleted_by_user_id = Auth::user()->user_id;
        $caseComment->save();

        $caseComment->delete();

        $case = Incident::where('incident_id', $incident_id)->first();

        return Redirect::route('case-comment.show', [$case->id]);
    }
}
