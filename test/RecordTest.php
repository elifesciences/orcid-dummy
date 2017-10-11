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
        $record = json_decode($response->getContent(), true);
        $person = $record['person'];
        $this->assertEquals(
            'Josiah',
            $person['name']['given-names']['value']
        );
        $this->assertEquals(
            'Carberry',
            $person['name']['family-name']['value']
        );

        $emails = $person['emails']['email'];
        $this->assertCount(1, $emails);
        $email = $emails[0];
        $this->assertEquals('LIMITED', $email['visibility']);
        $this->assertTrue($email['verified']);
        $this->assertTrue($email['primary']);
        $this->assertEquals('j.carberry@orcid.org', $email['email']);
        $this->assertArrayHasKey('activities-summary', $record);
        $this->assertArrayHasKey('employments', $record['activities-summary']);
        $this->assertArrayHasKey('employment-summary', $record['activities-summary']['employments']);
        $employments = $record['activities-summary']['employments']['employment-summary'];
        $this->assertCount(1, $employments);
        $employment = $employments[0];
        $this->assertEquals('eLife Sciences Publications Ltd', $employment['organization']['name']);
        $this->assertEquals(
            [
                'country' => 'GB',
                'region' => 'Cambridgeshire',
                'city' => 'Cambridge',
            ],
            $employment['organization']['address']
        );
        $this->assertNull($employment['end-date']);
        $this->assertEquals('LIMITED', $employment['visibility']);
    }
}
