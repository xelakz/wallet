<?php

namespace Tests\Feature;

class WalletTest extends OauthTest
{
    public function testCredit()
    {
        $this->clientCredentialsGrant();
        $this->fillWallet();
    }

    public function testDebit()
    {
        $this->clientCredentialsGrant();
        $this->fillWallet();
        $response = $this->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . $this->token
            ])->json('POST', '/api/v1/wallet/debit', [
                'uuid'      => 'test123-test123-test123-test123',
                'currency'  => 'CNY',
                'amount'    => 500,
                'reason'    => 'test reason'
            ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);
    }

    public function testUserBalance()
    {
        $this->clientCredentialsGrant();
        $this->fillWallet();
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization'    => 'Bearer ' . $this->token
        ])->json('GET', '/api/v1/wallet/balance', [
            'uuid' => 'test123-test123-test123-test123'
        ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);

    }

    public function testBatchBalance()
    {
        $this->clientCredentialsGrant();
        $this->fillWallet();
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization'    => 'Bearer ' . $this->token
        ])->json('GET', '/api/v1/wallet/balance/batch', [
            'uuids' => [0 => 'test123-test123-test123-test123']
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
