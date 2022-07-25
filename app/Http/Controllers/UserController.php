<?php

namespace App\Http\Controllers;

use Response;
use Inertia\Inertia;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\AjaxRequest;
use \Illuminate\Support\Facades\Auth;

class UserController extends Controller
{

    /**
     * Display home page
     */
    public function index(Request $request){
        return view('welcome');
    }

    /**
     * fetch active support users
     */
    public function activeUsers(AjaxRequest $request){
        $users = User::whereEndDate(null)->whereDisabled(false)->get();
        return Response::json(['status' => true, 'users' => $users]);
    }

    /**
     * fetch cancelled support users
     */
    public function cancelledUsers(AjaxRequest $request){
        $users = User::where('end_date', '!=', null)->whereDisabled(true)->get();
        return Response::json(['status' => true, 'users' => $users]);
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function dashboard(Request $request){
        return Inertia::render('Dashboard');
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function reports(Request $request){
        return Inertia::render('Reports');
    }

    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
