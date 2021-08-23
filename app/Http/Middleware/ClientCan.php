<?php

namespace App\Http\Middleware;

use App\Models\OauthAccessToken;
use Closure;
use Illuminate\Support\Facades\Log;

class ClientCan
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $currentPath = $request->path();

        $canPath = [
            'api/v1/wallet'     => ['wallet', 'admin'],
            'api/v1/client'     => 'admin',
            'api/v1/currencies' => 'admin',
            'api/v1/currency'   => 'admin',
            'api/v1/balances'   => 'admin'
        ];

        $token  = $request->bearerToken();
        $scopes = OauthAccessToken::getScopesViaBearer($token);
        if ($scopes == '*') {
            return $next($request);
        } else if (!empty($scopes)) {
            $scopesArray = array_map('trim', explode(',', $scopes));
            foreach ($canPath as $similarPath => $canScopes) {
                if (strpos($currentPath, $similarPath) === 0) {
                    if (is_array($canScopes)) {
                        foreach ($canScopes as $scope) {
                            if (in_array($scope, $scopesArray)) {
                                return $next($request);
                            }
                        }
                    } else if (in_array($canScopes, $scopesArray)) {
                        return $next($request);
                    }
                }
            }
        }

        Log::info("The scope is not covered this access. - " . $request->bearerToken());
        return response()->json([
            'status'      => true,
            'status_code' => 401,
            'error'       => trans('responses.unauthorized')
        ], 401);
    }
}
