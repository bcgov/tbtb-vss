<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Response;

class ApiAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {

        if (Auth::check()) {
            return $next($request);
        }

        $token = request()->bearerToken();
        if(is_null($token)){
            return Response::json(['status' => false, 'error' => 'Missing token.'], 401);
        }
        $jwksUri = env('KEYCLOAK_CERT');
        $jwksJson = file_get_contents($jwksUri);
        $jwksData = json_decode($jwksJson, true);
        $matchingKey = null;
        foreach ($jwksData['keys'] as $key) {
            if (isset($key['use']) && $key['use'] === 'sig') {
                $matchingKey = $key;
                break;
            }
        }

        $wrappedPk = wordwrap($matchingKey['x5c'][0], 64, "\n", true);
        $pk = "-----BEGIN CERTIFICATE-----\n" . $wrappedPk . "\n-----END CERTIFICATE-----";

        $decoded = null;
        try {
            $decoded = JWT::decode($token, new Key($pk, 'RS256'));
        } catch (ExpiredException $e) {
            return Response::json(['status' => false, 'error' => 'Token has expired.', 'auth' => Auth::check()], 401);
        } catch (\Exception $e) {
            return Response::json(['status' => false, 'error' => "An error occurred: " . $e->getMessage(), 'auth' => Auth::check()], 401);
        }

        if(is_null($decoded)) {
            return Response::json(['status' => false, 'error' => "Invalid token.", 'auth' => Auth::check()], 401);
        }else{
            //only validate for accounts that we have registered
            if($decoded->clientId === env('SERVICE_ACCOUNT')){
                $user = User::where('service_account', $decoded->clientId)->first();
                if(!is_null($user)){
                    Auth::login($user);
                    return $next($request);
                }
            }
        }

        return Response::json(['status' => false, 'error' => "Generic error.", 'auth' => Auth::check()], 401);
    }
}
