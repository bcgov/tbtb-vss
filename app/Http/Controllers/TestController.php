<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Response;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    public function test(Request $request)
    {
        return Response::json(['status' => true, 'error' => "", "user" => Auth::user()]);
    }

}
