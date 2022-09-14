<?php

namespace App\Http\Controllers;

use App\Http\Requests\AjaxRequest;
use App\Models\CaseComment;
use App\Models\Incident;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Response;
use Stevenmaguire\OAuth2\Client\Provider\Keycloak;

class UserController extends Controller
{
    public function appLogin(Request $request)
    {
        $provider = new Keycloak([
            'authServerUrl' => env('KEYCLOAK_SERVER_URL'),
            'realm' => env('KEYCLOAK_REALM'),
            'clientId' => env('KEYCLOAK_CLIENT_ID'),
            'clientSecret' => env('KEYCLOAK_CLIENT_SECRET'),
            'redirectUri' => env('KEYCLOAK_REDIRECT_URI'),
        ]);

        if (! $request->has('code')) {
            // If we don't have an authorization code then get one
            $authUrl = $provider->getAuthorizationUrl();
            $request->session()->put('oauth2state', $provider->getState());

            return Redirect::to($authUrl);

        // Check given state against previously stored one to mitigate CSRF attack
        } elseif (! $request->has('state') || ($request->state !== $request->session()->get('oauth2state'))) {
            $request->session()->forget('oauth2state');
            //Invalid state, make sure HTTP sessions are enabled
            return Inertia::render('Auth/Login', [
                'loginAttempt' => true,
                'hasAccess' => false,
                'status' => 'We could not log you in. Please contact RequestIT.',
            ]);
        } else {
            // Try to get an access token (using the authorization coe grant)
            try {
                $token = $provider->getAccessToken('authorization_code', [
                    'code' => $request->code,
                ]);
            } catch (\Exception $e) {
                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Failed to get access token: '.$e->getMessage(),
                ]);
            }

            // Optional: Now you have a token you can look up a users profile data
            try {
                // We got an access token, let's now get the user's details
                $idir_user = $provider->getResourceOwner($token);
                $idir_user = $idir_user->toArray();
            } catch (\Exception $e) {
                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Failed to get resource owner: '.$e->getMessage(),
                ]);
            }

            $user = User::where('idir_user_guid', 'ilike', $idir_user['idir_user_guid'])->first();

            //if it is a new IDIR user, register the user first
            if (is_null($user)) {
                $this->newUser($idir_user);

                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Please contact VSS Admin to grant you access.',
                ]);

            //if the user has been disabled
            } elseif ($user->disabled === true) {
                return Inertia::render('Auth/Login', [
                    'loginAttempt' => true,
                    'hasAccess' => false,
                    'status' => 'Access denied. Please contact VSS Admin.',
                ]);
            }

            //else the user has access
            Auth::login($user);

            return Redirect::route('dashboard');
        }
    }

    /**
     * Display home page
     */
    public function index(Request $request)
    {
        return view('welcome');
    }

    /**
     * fetch active support users
     */
    public function activeUsers(AjaxRequest $request)
    {
        $users = User::whereEndDate(null)->whereDisabled(false)->get();

        return Response::json(['status' => true, 'users' => $users]);
    }

    /**
     * fetch cancelled support users
     */
    public function cancelledUsers(AjaxRequest $request)
    {
        $users = User::where('end_date', '!=', null)->whereDisabled(true)->get();

        return Response::json(['status' => true, 'users' => $users]);
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function dashboard(Request $request)
    {
        return Inertia::render('Dashboard');
    }

    /**
     * Display first page after login (dashboard page)
     */
    public function reports(Request $request)
    {
        return Inertia::render('Reports', ['results' => null]);
    }

    /**
     * Display the login view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function login(Request $request)
    {
        return Inertia::render('Auth/Login', [
            'loginAttempt' => false,
            'hasAccess' => false,
            'status' => session('status'),
        ]);
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

    private function newUser($idir_user)
    {
        $user = new User();
        $user->user_id = Str::upper($idir_user['idir_username']);
        $user->first_name = $idir_user['given_name'];
        $user->last_name = $idir_user['family_name'];
        $user->start_date = date('Y-m-d', strtotime('now'));
        $user->end_date = null;
        $user->access_type = 'U';
        $user->disabled = true;
        $user->idir_user_guid = $idir_user['idir_user_guid'];
        $user->email = $idir_user['email'];
        $user->password = Hash::make($idir_user['idir_username']);
        $user->save();

        $sub_user_id = Str::upper(Str::substr($idir_user['idir_username'], 0, 4));
        $legacy_idir = User::where('user_id', $sub_user_id)->where('idir_user_guid', null)->first();

        //if found a user that has not been synced
        if (! is_null($legacy_idir)) {
            //user_id from MS Access came with only 4 characters
            //we need to use full IDIR ID and GUID

            $incidents = Incident::where('auditor_user_id', $sub_user_id)->get();
            foreach ($incidents as $incident) {
                $incident->auditor_user_id = Str::upper($idir_user['idir_username']);
                $incident->save();
            }

            $incidents = Incident::where('investigator_user_id', $sub_user_id)->get();
            foreach ($incidents as $incident) {
                $incident->auditor_user_id = Str::upper($idir_user['idir_username']);
                $incident->save();
            }

            $comments = CaseComment::where('staff_user_id', $sub_user_id)->get();
            foreach ($comments as $comment) {
                $comment->staff_user_id = Str::upper($idir_user['idir_username']);
                $comment->save();
            }

            //disable legacy account
            $legacy_idir->disabled = true;
            $legacy_idir->deleted_at = date('Y-m-d H:i:s', strtotime('now'));
            $legacy_idir->user_id = $legacy_idir->user_id . '-DELETED';
            $legacy_idir->save();
        }
    }
}
