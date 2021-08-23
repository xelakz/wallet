<?php

namespace App\Services;

use App\Requests\ClientRequests;
use Illuminate\Support\Facades\{DB, Log};
use App\Models\OauthClient;
use Carbon\Carbon;
use Exception;

class OauthClientService
{
    public static function clientAdd(ClientRequests $request)
    {
        try {
            if (!OauthClient::checkClient($request->client_id, $request->client_secret)) {
                DB::beginTransaction();

                $client = OauthClient::createClient($request->client_id, $request->client_secret, $request->name);

                Log::info('Client ' . $request->client_id . ' has been created.');

                DB::commit();

                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'message' => 'Client successfully created.',
                    'data'       => [
                        'name'          => $request->name,
                        'client_id'     => $request->client_id,
                        'client_secret' => $request->client_secret,
                        'revoked'       => $client->revoked,
                        'created_at'    => Carbon::createFromFormat('Y-m-d H:i:s', $client->created_at)->format('Y-m-d H:i:s')
                    ]
                ], 200);

            } else {
                Log::info('Client ' . $request->client_id . ' cannot be created.');

                return response()->json([
                    'status'      => false,
                    'status_code' => 400,
                    'errors'       => [
                        'message' => 'Client cannot be created.'
                    ]
                ], 400);
            }
        } catch (Exception $e) {
            DB::rollBack();

            Log::info('Creating client ' . $request->client_id . ' failed.');
            Log::error((array) $e);

            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }

    public static function clientRevoke(ClientRequests $request)
    {
        try {
            if (OauthClient::checkClient($request->client_id, $request->client_secret)) {
                DB::beginTransaction();


                OauthClient::revokeClient($request->client_id, $request->client_secret);
                Log::info('Client ' . $request->client_id . ' has been revoked.');

                DB::commit();

                return response()->json([
                    'status'      => true,
                    'status_code' => 200,
                    'data'        => [
                        'message' => 'Client successfully revoked.'
                    ]
                ], 200);
            } else {
                Log::info('Client ' . $request->client_id . ' missing or not available to be revoked.');
                return response()->json([
                    'status'      => false,
                    'status_code' => 400,
                    'errors'      => [
                        'message' => 'Client is missing or not available to be revoked.'
                    ]
                ], 400);
            }
        }
        catch (Exception $e) {
            DB::rollBack();

            Log::info('Revoking client ' . $request->client_id . ' failed.');
            Log::error((array) $e);

            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }
}
