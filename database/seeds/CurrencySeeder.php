<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    protected $tablename = "currencies";

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Currency::firstOrCreate(
            [
                'id'         => 1,
                'name'       => "CNY",
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]
        );
    }
}
