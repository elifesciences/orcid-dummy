<?php

namespace test\eLife\Orcid;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

final class OAuth2Test extends PHPUnit_Framework_TestCase
{
    use SilexTestCase;

    /**
     * @test
     */
    public function it_can_authorize_a_user()
    {
        $response = $this->getApp()->handle(Request::create(
            '/oauth2/authorize',
            'GET',
            [
                'response_type' => 'code',
                'client_id' => 'journal',
                'state' => 'unique_state_string',
                'redirect_uri' => 'https://example.com/check',
            ]
        ));

        $this->assertSame(
            302,
            $response->getStatusCode(),
            var_export($response->getContent(), true)
        );
        $this->assertSame(
            'https://example.com/check?code=code_unique_state_string&state=unique_state_string',
            $response->headers->get('Location')
        );
    }

    /**
     * @test
     */
    public function it_can_release_a_token()
    {
        $response = $this->getApp()->handle(Request::create(
            '/oauth2/token',
            'POST',
            [
                'client_id' => 'journal',
                'client_secret' => 'journal_secret',
                'redirect_uri' => 'https://example.com/check',
                'grant_type' => 'authorization_code',
                'code' => 'code_unique_string',
            ]
        ));

        $this->assertSame(
            200,
            $response->getStatusCode(),
            var_export($response->getContent(), true)
        );
        $this->assertSame(
            [
                'access_token' => 'access_token_code_unique_string',
                'token_type' => 'bearer',
                'refresh_token' => 'refresh_token_code_unique_string',
                'expires_in' => 30 * 24 * 60 * 60,
                'scope' => '/authenticate',
                'orcid' => '0000-0001-2345-6789',
                'name' => 'Jon Osterman',
            ],
            json_decode($response->getContent(), true)
        );
    }
}
