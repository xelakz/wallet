<?php

namespace Tests\Feature;
use Illuminate\Support\Carbon;
class TransactionTest extends OauthTest
{
    public function testUserTransactions()
    {
        $this->clientCredentialsGrant();
        $this->fillWallet();

        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization'    => 'Bearer ' . $this->token
        ])->json('GET', '/api/v1/wallet/transaction', [
            'uuid'          => 'test123-test123-test123-test123',
            'start_date'    => Carbon::now()->toDateString(),
            'end_date'      => Carbon::now()->toDateString()
        ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);

    }

    private function fillWallet()
    {
        $this->clientCredentialsGrant();
        $response = $this->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . $this->token
            ])->json('POST', '/api/v1/wallet/credit', [
                'uuid'      => 'test123-test123-test123-test123',
                'currency'  => 'CNY',
                'amount'    => 1000,
                'reason'    => 'test reason'
            ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);
    }
}
