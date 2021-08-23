<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OauthTest extends TestCase
{
    use RefreshDatabase;

    public $token;
    public $refresh_token;

    public function testClientCredentialsGrant()
    {
        $this->clientCredentialsGrant();
        $this->refreshTokenGrant();
    }

    protected function clientCredentialsGrant()
    {
        $response = $this->json('POST', '/api/v1/oauth/token',
                [
                    'client_id'  => '9pinetech',
                    'client_secret' => '9p!n3t3ch53cr3+',
                    'grant_type' => 'client_credentials',
                ]
            );

        $response->assertStatus(200);
        $responseData = json_decode($response->getContent(), true);
        $this->token = $responseData['data']['access_token'];
        $this->refresh_token = $responseData['data']['refresh_token'];
    }

    protected function refreshTokenGrant()
    {
        if (!empty($this->refresh_token)) {
            $response = $this->json('POST', '/api/v1/oauth/token',
                [
                    'client_id'  => '9pinetech',
                    'client_secret' => '9p!n3t3ch53cr3+',
                    'grant_type' => 'client_credentials',
                    'refresh_token' => $this->refresh_token
                ]
            );

            $response->assertStatus(200);
        }
    }
}
