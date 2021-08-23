<?php

namespace App\Http\Controllers;

use App\Requests\ClientRequests;
use Illuminate\Http\Request;
use App\Models\OauthClient;
use App\Facades\OauthClient as OauthClientFacade;

class ClientController extends Controller
{
    public function getList(Request $request, $limitNumber = 10, $pageNumber = 1)
    {
        try {
            $offset = ($pageNumber * $limitNumber) - $limitNumber;

            $clients = OauthClient::getList($offset, $limitNumber);

            return response()->json([
                'status'      => true,
                'status_code' => 200,
                'data'        => $clients
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status'      => false,
                'status_code' => 500,
                'error'       => trans('responses.internal-error')
            ], 500);
        }
    }

    public function addClient(ClientRequests $request)
    {
        return OauthClientFacade::clientAdd($request);
    }

    public function revokeClient(ClientRequests $request)
    {
        return OauthClientFacade::clientRevoke($request);
    }
}
