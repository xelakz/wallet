<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OauthRefreshToken extends Model
{
    public $incrementing = false;
    public $timestamps   = false;

    protected $table      = "oauth_refresh_tokens";
    protected $primaryKey = 'refresh_token';
    protected $fillable   = [
        'refresh_token',
        'access_token',
        'revoked',
        'expires_at'
    ];

    public static function createRefreshToken(string $token): string
    {
        $refreshToken = md5('refresh-' . uniqid());

        self::create([
            'refresh_token' => $refreshToken,
            'access_token'  => $token,
            'revoked'       => false,
            'expires_at'    => Carbon::now()->addDays(30)->format('Y-m-d H:i:s')
        ]);

        return $refreshToken;
    }

    public static function getDataViaRefreshToken(string $refreshToken): ?array
    {
        $now              = Carbon::now();
        $refreshTokenData = self::where('refresh_token', $refreshToken)
                                ->where('revoked', false)
                                ->where('expires_at', '>', $now->format('Y-m-d H:i:s'))
                                ->first();

        if ($refreshTokenData) {
            $token = OauthAccessToken::updateTokenExpiry($refreshTokenData->access_token);
        } else {
            return null;
        }

        $expiresIn = $now->diffInSeconds($token->expires_at);

        return [
            'access_token'  => $token->token,
            'refresh_token' => $refreshToken,
            'expires_in'    => $expiresIn
        ];
    }
}
