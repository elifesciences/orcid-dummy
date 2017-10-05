<?php

namespace test\eLife\Orcid;

use PHPUnit_Framework_TestCase;
use Symfony\Component\HttpFoundation\Request;

final class RecordTest extends PHPUnit_Framework_TestCase
{
    use SilexTestCase;

    /**
     * @test
     */
    public function it_provides_a_record_for_the_user()
    {
        $response = $this->getApp()->handle(Request::create($a = '/api/v2.0/0000-0002-1825-0097/record'));

        $this->assertSame(
            200,
            $response->getStatusCode(),
            var_export($response->getContent(), true)
        );
        $record = new \SimpleXMLElement($response->getContent());
        $person = $record->children('person', true);
        $this->assertEquals(
            'Josiah',
            $record->xpath('//person:person/person:name/personal-details:given-names')[0]
        );
        $this->assertEquals(
            'Carberry',
             $record->xpath('//person:person/person:name/personal-details:family-name')[0]
        );

        $email = $record->xpath('//person:person/email:emails/email:email')[0];
        $this->assertEquals('limited', (string) $email->attributes()->visibility);
        $this->assertEquals('true', (string) $email->attributes()->primary);
        $this->assertEquals('true', (string) $email->attributes()->verified);
        $this->assertEquals('j.carberry@orcid.org', $email->xpath('email:email')[0]);
    }
}
