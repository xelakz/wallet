<?php

use Illuminate\Database\Seeder;
use App\Models\OauthClient;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        OauthClient::firstOrCreate([
            'client_id'     => '9pinetech',
            'client_secret' => '9p!n3t3ch53cr3+',
            'name'          => "Main Client",
            'revoked'       => false
        ]);
    }
}
