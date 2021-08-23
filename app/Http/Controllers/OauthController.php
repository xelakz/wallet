<?php

namespace App\Http\Controllers;

use App\Requests\OauthTokenRequests;
use App\Facades\OauthToken as OauthTokenFacade;

class OauthController extends Controller
{
    public function token(OauthTokenRequests $request)
    {
        return OauthTokenFacade::token($request);
    }
}
