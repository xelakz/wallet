<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OauthClient extends Model
{
    public $incrementing = false;

    protected $table      = "oauth_clients";
    protected $primaryKey = 'client_id';
    protected $fillable   = [
        'client_id',
        'client_secret',
        'name',
        'revoked',
    ];

    public static function checkClient(string $clientId, string $secret)
    {
        return self::where('client_id', $clientId)
                   ->where('client_secret', $secret)
                   ->where('revoked', false)
                   ->exists();
    }

    public static function getList(int $offset, int $limit)
    {
        return self::offset($offset)->limit($limit)->get()->toArray();
    }

    public static function createClient(string $clientId, string $secret, string $name)
    {
        return self::create([
            'client_id' => $clientId,
            'client_secret' => $secret,
            'name' => $name,
            'revoked' => false
        ]);
    }

    public static function revokeClient(string $clientId, string $secret)
    {
        return self::where('client_id', $clientId)
                   ->where('client_secret', $secret)
                   ->update(['revoked' => true]);
    }
}
