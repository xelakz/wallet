<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';
    public $timestamps = ["created_at"]; //only want to used created_at column
    const UPDATED_AT = null; //and updated by default null set
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'amount',
        'type',
        'reason',
        'currency_id'
    ];
}
