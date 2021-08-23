<?php

namespace Tests\Feature;

class CurrencyTest extends OauthTest
{
    public function addCurrency()
    {
        $this->clientCredentialsGrant();
        $response = $this->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . $this->token
            ])->json('POST', '/api/v1/currency/create', [
                'name'          => 'PHP',
                'is_enabled'    => 1
            ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);
    }

    public function testUpdateCurrency()
    {
        $this->clientCredentialsGrant();
        $response = $this->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . $this->token
            ])->json('POST', '/api/v1/currency/update', [
                'name'          => 'CNY',
                'is_enabled'    => 1
            ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);
    }

    public function testCurrencyList()
    {
        $this->clientCredentialsGrant();
        $response = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest',
            'Authorization'    => 'Bearer ' . $this->token
        ])->json('GET', '/api/v1/currency/list');
        $responseData = $response->getContent();
        $response->assertStatus(200);

    }
}
