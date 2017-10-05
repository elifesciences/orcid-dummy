<?php

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

require_once __DIR__.'/../vendor/autoload.php';

$app = new Application();

$app->get('/ping', function () {
    return new Response(
        'pong',
        Response::HTTP_OK,
        [
            'Cache-Control' => 'must-revalidate, no-cache, no-store, private',
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]
    );
});

$app->get('/oauth2/authorize', function (Request $request) {
    $redirectUri = $request->get('redirect_uri');
    $state = $request->get('state');
    $code = 'code_'.$state;
    $location = $redirectUri.'?'.http_build_query([
        'code' => $code,
        'state' => $state,
    ]);

    return new Response(
        'pong',
        Response::HTTP_FOUND,
        [
            'Location' => $location,
        ]
    );
});

$app->post('/oauth2/token', function (Request $request) {
    $code = $request->get('code');

    return new JsonResponse([
        'access_token' => 'access_token_'.$code,
        'token_type' => 'bearer',
        //'refresh_token' => 'refresh_token_'.$code,
        'expires_in' => 30 * 24 * 60 * 60,
        'scope' => '/authenticate',
        'orcid' => '0000-0002-1825-0097',
        'name' => 'Josiah Carberry',
    ]);
});

$app->get('/api/v2.0/0000-0002-1285-0097/record', function (Request $request) {
    $body = <<<'EOT'
<?xml version="1.0" encoding="UTF-8" standalone="yes"?>
<record:record path="/0000-0002-1825-0097" xmlns:internal="http://www.orcid.org/ns/internal" xmlns:funding="http://www.orcid.org/ns/funding" xmlns:preferences="http://www.orcid.org/ns/preferences" xmlns:address="http://www.orcid.org/ns/address" xmlns:education="http://www.orcid.org/ns/education" xmlns:work="http://www.orcid.org/ns/work" xmlns:deprecated="http://www.orcid.org/ns/deprecated" xmlns:other-name="http://www.orcid.org/ns/other-name" xmlns:history="http://www.orcid.org/ns/history" xmlns:employment="http://www.orcid.org/ns/employment" xmlns:error="http://www.orcid.org/ns/error" xmlns:common="http://www.orcid.org/ns/common" xmlns:person="http://www.orcid.org/ns/person" xmlns:activities="http://www.orcid.org/ns/activities" xmlns:record="http://www.orcid.org/ns/record" xmlns:researcher-url="http://www.orcid.org/ns/researcher-url" xmlns:peer-review="http://www.orcid.org/ns/peer-review" xmlns:personal-details="http://www.orcid.org/ns/personal-details" xmlns:bulk="http://www.orcid.org/ns/bulk" xmlns:keyword="http://www.orcid.org/ns/keyword" xmlns:email="http://www.orcid.org/ns/email" xmlns:external-identifier="http://www.orcid.org/ns/external-identifier">
    <common:orcid-identifier>
        <common:uri>http://sandbox.orcid.org/0000-0002-1825-0097</common:uri>
        <common:path>0000-0002-1825-0097</common:path>
        <common:host>sandbox.orcid.org</common:host>
    </common:orcid-identifier>
    <preferences:preferences>
        <preferences:locale>en</preferences:locale>
    </preferences:preferences>
    <history:history>
        <history:creation-method>Direct</history:creation-method>
        <history:submission-date>2017-10-05T09:01:04.543Z</history:submission-date>
        <common:last-modified-date>2017-10-05T09:14:59.315Z</common:last-modified-date>
        <history:claimed>true</history:claimed>
        <history:verified-email>false</history:verified-email>
        <history:verified-primary-email>false</history:verified-primary-email>
    </history:history>
    <person:person path="/0000-0002-1825-0097/person">
        <person:name visibility="public" path="0000-0002-1825-0097">
            <common:created-date>2017-10-05T09:01:04.543Z</common:created-date>
            <common:last-modified-date>2017-10-05T09:01:04.768Z</common:last-modified-date>
            <personal-details:given-names>Josiah</personal-details:given-names>
            <personal-details:family-name>Carberry</personal-details:family-name>
        </person:name>
        <other-name:other-names path="/0000-0002-1825-0097/other-names"/>
        <researcher-url:researcher-urls path="/0000-0002-1825-0097/researcher-urls"/>
        <email:emails path="/0000-0002-1825-0097/email">
            <common:last-modified-date>2017-10-05T09:01:04.768Z</common:last-modified-date>
            <email:email visibility="limited" verified="true" primary="true">
                <common:created-date>2017-10-05T09:01:04.768Z</common:created-date>
                <common:last-modified-date>2017-10-05T09:01:04.768Z</common:last-modified-date>
                <common:source>
                    <common:source-orcid>
                        <common:uri>http://sandbox.orcid.org/0000-0002-1825-0097</common:uri>
                        <common:path>0000-0002-1825-0097</common:path>
                        <common:host>sandbox.orcid.org</common:host>
                    </common:source-orcid>
                    <common:source-name>Josiah Carberry</common:source-name>
                </common:source>
                <email:email>j.carberry@orcid.org</email:email>
            </email:email>
		</email:emails> 
        <address:addresses path="/0000-0002-1825-0097/address"/>
        <keyword:keywords path="/0000-0002-1825-0097/keywords"/>
        <external-identifier:external-identifiers path="/0000-0002-1825-0097/external-identifiers"/>
    </person:person>
    <activities:activities-summary path="/0000-0002-1825-0097/activities">
        <activities:educations path="/0000-0002-1825-0097/educations"/>
        <activities:employments path="/0000-0002-1825-0097/employments"/>
        <activities:fundings path="/0000-0002-1825-0097/fundings"/>
        <activities:peer-reviews path="/0000-0002-1825-0097/peer-reviews"/>
        <activities:works path="/0000-0002-1825-0097/works"/>
    </activities:activities-summary>
</record:record>
EOT;

    return new Response($body);
});

$app->error(function (Throwable $e) {
    if ($e instanceof HttpExceptionInterface) {
        $status = $e->getStatusCode();
    } else {
        $status = 500;
    }

    return new JsonResponse(
        [
            'message' => $e->getMessage(),
        ],
        $status
    );
});

return $app;
