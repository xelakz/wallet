<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    protected $table = 'currencies';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'is_enabled'
    ];

    public static function list()
    {
        return self::select([
            'name',
            'is_enabled',
            'created_at',
            'updated_at'
        ])
        ->orderBy('created_at', 'DESC')
        ->get()
        ->toArray();
    }

    public static function getCurrencyId($name)
    {
        $currency = self::where(['name' => $name, 'is_enabled' => true])->first();
        return !empty($currency) ? $currency->id : null;
    }

    public static function getAllCurrencies()
    {
        $currencies = self::select([
                'id',
                'name'
            ])
            ->get();

        $list = $currencies->pluck('name', 'id');

        return !empty($list) ? $list : null;
    }
    public static function getNameById($id)
    {
        return self::where('id', $id)->first()->name;
    }
}
