<?php

namespace App\Services;

use App\Requests\OauthTokenRequests;
use Illuminate\Support\Facades\Log;
use App\Models\{OauthClient, OauthAccessToken, OauthRefreshToken};

class OauthTokenService
{
    public static function token(OauthTokenRequests $request)
    {
        try {
            $client = OauthClient::checkClient($request->client_id, $request->client_secret);

            if ($client === true) {
                if ($request->grant_type == 'client_credentials') {
                    list('access_token' => $accessToken, 'refresh_token' => $refreshToken, 'expires_in' => $expiresIn) = OauthAccessToken::getDataViaClientCredentials($request);
                    Log::info('Access Token ' . $accessToken . ' has been created.');
                } else if ($request->grant_type == 'refresh_token') {
                    $refreshTokenData = OauthRefreshToken::getDataViaRefreshToken($request->refresh_token);
                    if (is_array($refreshTokenData)) {
                        list('access_token' => $accessToken, 'refresh_token' => $refreshToken, 'expires_in' => $expiresIn) = $refreshTokenData;
                        Log::info('Access Token ' . $accessToken . ' has been refreshed.');
                    } else {
                        Log::info('Invalid refresh token ' . $request->refresh_token);
                        return response()->json([
                            'status'      => false,
                            'status_code' => 400,
                            'errors'      => ['refresh_token' => ["The refresh_token is invalid."]]
                        ], 400);
                    }
                }

                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'data'        => [
                        'access_token'  => $accessToken,
                        'refresh_token' => $refreshToken,
                        'expires_in'    => $expiresIn
                    ]
                ], 200);
            } else {
                Log::info('Unauthorized ' . $request->client_id);
                return response()->json([
                    'status'      => false,
                    'status_code' => 401,
                    'error'       => trans('responses.unauthorized')
                ], 401);
            }
        } catch (Exception $e) {
            Log::error([
                $e->getMessage,
                $e->getFile,
                $e->getLine
            ]);
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }
}
