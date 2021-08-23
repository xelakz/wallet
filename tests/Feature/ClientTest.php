<?php

namespace Tests\Feature;

class Client extends OauthTest
{
    public function testClientList()
    {
        $this->clientCredentialsGrant();
        $paths = [
            '/api/v1/clients',
            '/api/v1/clients/10',
            '/api/v1/clients/10/1'
        ];
        foreach ($paths as $path) {
            $response = $this->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . $this->token
            ])->json('GET', $path);
                $responseData = $response->getContent();
                $response->assertStatus(200);
        }
    }

    public function testClientCreateAndRevoke()
    {
        $this->clientCredentialsGrant();
        $this->clientCreate();
        $this->clientRevoke();
    }

    private function clientCreate()
    {
        $response = $this->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . $this->token
            ])->json('POST', '/api/v1/client/create', [
                'name' => 'sample',
                'client_id' => 'sample',
                'client_secret' => 'sample'
            ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);
    }

    private function clientRevoke()
    {
        $response = $this->withHeaders([
                'X-Requested-With' => 'XMLHttpRequest',
                'Authorization'    => 'Bearer ' . $this->token
            ])->json('POST', '/api/v1/client/revoke', [
                'client_id' => 'sample',
                'client_secret' => 'sample'
            ]);
        $responseData = $response->getContent();
        $response->assertStatus(200);
    }
}
