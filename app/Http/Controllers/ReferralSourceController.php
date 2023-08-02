<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReferralSourceStoreRequest;
use App\Models\ReferralSource;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ReferralSourceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index()
    {
        $referrals = ReferralSource::orderBy('referral_code', 'asc')->get();

        return Inertia::render('Maintenance', ['status' => true, 'results' => $referrals, 'page' => 'referral-source']);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(ReferralSourceStoreRequest $request)
    {
        ReferralSource::create($request->validated());

        return Redirect::route('maintenance.referral-source.index');
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ReferralSourceStoreRequest $request, ReferralSource $referralSource)
    {
        ReferralSource::where('id', $referralSource->id)->update($request->validated());

        return Redirect::route('maintenance.referral-source.index');
    }
}
