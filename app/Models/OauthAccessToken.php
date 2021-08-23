<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Requests\OauthTokenRequests;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OauthAccessToken extends Model
{
    public $incrementing = false;

    protected $table      = "oauth_access_tokens";
    protected $primaryKey = 'token';
    protected $fillable   = [
        'token',
        'uuid',
        'client_id',
        'scopes',
        'revoked',
        'created_at',
        'updated_at',
        'expires_at'
    ];

    public static function getDataViaClientCredentials(OauthTokenRequests $request): array
    {
        $token     = md5(uniqid());
        $expiresAt = Carbon::now()->addDays(15)->format('Y-m-d H:i:s');
        $now       = Carbon::now();

        $expiresIn = Carbon::now()->diffInSeconds($expiresAt);

        self::create([
            'token'      => $token,
            'client_id'  => $request->client_id,
            'revoked'    => false,
            'expires_at' => $expiresAt,
            'scopes'     => $request->scopes ?? '*'
        ]);

        $refreshToken = OauthRefreshToken::createRefreshToken($token);

        return [
            'access_token'  => $token,
            'refresh_token' => $refreshToken,
            'expires_in'    => $expiresIn
        ];
    }

    public static function updateTokenExpiry(string $token): OauthAccessToken
    {
        $expiresAt = Carbon::now()->addDays(15)->format('Y-m-d H:i:s');

        $selfData             = self::where('token', $token)->first();
        $selfData->expires_at = $expiresAt;
        $selfData->save();

        return $selfData;
    }

    public static function getScopesViaBearer(?string $token): ?string
    {
        $selfData = self::where('token', $token)
                        ->where('revoked', false)->first();
        if ($selfData) {
            return $selfData->scopes;
        } else {
            return null;
        }
    }
}
