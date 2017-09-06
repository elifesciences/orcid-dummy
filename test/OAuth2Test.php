<?php

namespace test\eLife\Orcid;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

final class Oauth2Test extends PHPUnit_Framework_TestCase
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
}
