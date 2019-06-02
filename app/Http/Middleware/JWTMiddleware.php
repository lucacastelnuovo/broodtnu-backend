<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use App\Helpers\JWTHelper;
use Illuminate\Http\Request;

class JWTMiddleware {
    /**
     * Validate JWT token
     *
     * @param Request   $request
     * @param Closure   $next
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $access_token = JWTHelper::parseAuthHeader($request);
        $credentials = JWTHelper::authenticate($access_token, $request->ip());

        // Returns an error message for an invalid token
        if (isset($credentials->error)) {
            $http_code = $credentials->http;
            unset($credentials->http);
            return response()->json($credentials, $http_code);
        }

        // Put the user in the request
        $request->user = User::findOrFail($credentials->sub);

        // Put the JWT in the request
        $request->jwt = $credentials;

        return $next($request);
    }
}