<?php

namespace App\Http\Controllers;

use App\Http\Requests\StaffEditRequest;
use App\Models\AreaOfAudit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;

class MaintenanceController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response::render
     */
    public function staffList(Request $request): \Inertia\Response
    {
        $staff = User::orderBy('created_at', 'desc')->get();
        return Inertia::render('Maintenance', ['status' => true, 'results' => $staff, 'page' => 'staff']);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Inertia\Response::render
     */
    public function staffShow(Request $request, User $user): \Inertia\Response
    {
        //$staff = User::orderBy('created_at', 'desc')->get();
        return Inertia::render('Maintenance', ['status' => true, 'results' => $user, 'page' => 'staff-edit']);
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \App\Http\Requests\StaffEditRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\RedirectResponse::render
     */
    public function staffEdit(StaffEditRequest $request, User $user): \Illuminate\Http\RedirectResponse
    {
        if(Auth::user()->access_type !== 'U'){

            $user->user_id = $request->user_id;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->start_date = $request->start_date;
            $user->end_date = $request->end_date;
            $user->access_type = $request->access_type;
            $user->disabled = $request->disabled;
            $user->email = $request->email;

            if(!is_null($request->password)){
                $user->password = Hash::make($request->password);
            }
            $user->save();
        }

        $user = User::find($user->id);

        return Redirect::route('maintenance.staff.list');

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

}
