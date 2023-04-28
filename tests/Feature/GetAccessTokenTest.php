<?php

namespace QuetzalStudio\SnapBi\Tests\Feature;

use Illuminate\Support\Facades\Http;
use QuetzalStudio\SnapBi\AccessToken;
use QuetzalStudio\SnapBi\Auth\AccessToken as AuthAccessToken;
use QuetzalStudio\SnapBi\Config;
use QuetzalStudio\SnapBi\Tests\TestCase;

class GetAccessTokenTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Http::fake([
            'http://localhost.test/api-test/v1.0/access-token/b2b' => Http::response([
                'responseCode' => '2007300',
                'responseMessage' => 'Sucessful',
                'accessToken' => 'nd8vzUzOHlbKf82Hcn5VP22SdO56WKAoQC7mExbTfd68tPBzQ84Ocv',
                'tokenType' => 'Bearer',
                'expiresIn' => '900',
            ]),
        ]);
    }

    public function testGettingAcessToken()
    {
        Config::load('test');

        $resp = (new AuthAccessToken())->get();

        $this->assertTrue($resp->status() == 200);
        $this->assertArrayHasKey('responseCode', $resp->json());
        $this->assertArrayHasKey('responseMessage', $resp->json());
        $this->assertArrayHasKey('accessToken', $resp->json());
        $this->assertArrayHasKey('tokenType', $resp->json());
        $this->assertArrayHasKey('expiresIn', $resp->json());
    }

    public function testGettingAccessTokenFromCache()
    {
        $token = (string) AccessToken::get('test');

        $this->assertTrue(strlen($token) > 10);
    }
}
