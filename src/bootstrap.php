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

$app->get('/api/v2.0/0000-0002-1825-0097/record', function (Request $request) {
    $body = <<<'EOT'
{
  "path": "/0000-0002-1825-0097",
  "activities-summary": {
    "path": "/0000-0002-1825-0097/activities",
    "works": {
      "path": "/0000-0002-1825-0097/works",
      "group": [],
      "last-modified-date": null
    },
    "peer-reviews": {
      "path": "/0000-0002-1825-0097/peer-reviews",
      "group": [],
      "last-modified-date": null
    },
    "fundings": {
      "path": "/0000-0002-1825-0097/fundings",
      "group": [],
      "last-modified-date": null
    },
    "employments": {
      "path": "/0000-0001-8778-8651/employments",
      "employment-summary": [
        {
          "path": "/0000-0001-8778-8651/employment/28724",
          "put-code": 28724,
          "visibility": "LIMITED",
          "created-date": {
            "value": 1507638718479
          },
          "last-modified-date": {
            "value": 1507638718479
          },
          "source": {
            "source-name": {
              "value": "Alfred Pennyworth"
            },
            "source-client-id": null,
            "source-orcid": {
              "host": "sandbox.orcid.org",
              "path": "0000-0001-8778-8651",
              "uri": "http://sandbox.orcid.org/0000-0001-8778-8651"
            }
          },
          "department-name": null,
          "role-title": "Robot",
          "start-date": {
            "day": {
              "value": "01"
            },
            "month": {
              "value": "01"
            },
            "year": {
              "value": "1913"
            }
          },
          "end-date": null,
          "organization": {
            "disambiguated-organization": {
              "disambiguation-source": "RINGGOLD",
              "disambiguated-organization-identifier": "385480"
            },
            "address": {
              "country": "GB",
              "region": "Cambridgeshire",
              "city": "Cambridge"
            },
            "name": "eLife Sciences Publications Ltd"
          }
        }
      ],
      "last-modified-date": {
        "value": 1507638718479
      }
    },
    "educations": {
      "path": "/0000-0002-1825-0097/educations",
      "education-summary": [],
      "last-modified-date": null
    },
    "last-modified-date": null
  },
  "person": {
    "path": "/0000-0002-1825-0097/person",
    "external-identifiers": {
      "path": "/0000-0002-1825-0097/external-identifiers",
      "external-identifier": [],
      "last-modified-date": null
    },
    "last-modified-date": {
      "value": 1507194064768
    },
    "name": {
      "path": "0000-0002-1825-0097",
      "visibility": "PUBLIC",
      "source": null,
      "credit-name": null,
      "family-name": {
        "value": "Carberry"
      },
      "given-names": {
        "value": "Josiah"
      },
      "last-modified-date": {
        "value": 1507194064768
      },
      "created-date": {
        "value": 1507194064543
      }
    },
    "other-names": {
      "path": "/0000-0002-1825-0097/other-names",
      "other-name": [],
      "last-modified-date": null
    },
    "biography": null,
    "researcher-urls": {
      "path": "/0000-0002-1825-0097/researcher-urls",
      "researcher-url": [],
      "last-modified-date": null
    },
    "emails": {
      "path": "/0000-0002-1825-0097/email",
      "email": [
        {
          "put-code": null,
          "created-date": {
            "value": 1507194064768
          },
          "last-modified-date": {
            "value": 1507194064768
          },
          "source": {
            "source-name": {
              "value": "Josiah Carberry"
            },
            "source-client-id": null,
            "source-orcid": {
              "host": "sandbox.orcid.org",
              "path": "0000-0002-1825-0097",
              "uri": "http://sandbox.orcid.org/0000-0002-1825-0097"
            }
          },
          "email": "j.carberry@orcid.org",
          "path": null,
          "visibility": "LIMITED",
          "verified": true,
          "primary": true
        }
      ],
      "last-modified-date": {
        "value": 1507194064768
      }
    },
    "addresses": {
      "path": "/0000-0002-1825-0097/address",
      "address": [],
      "last-modified-date": null
    },
    "keywords": {
      "path": "/0000-0002-1825-0097/keywords",
      "keyword": [],
      "last-modified-date": null
    }
  },
  "history": {
    "verified-primary-email": false,
    "creation-method": "DIRECT",
    "completion-date": null,
    "submission-date": {
      "value": 1507194064543
    },
    "last-modified-date": {
      "value": 1507200680619
    },
    "claimed": true,
    "source": null,
    "deactivation-date": null,
    "verified-email": false
  },
  "preferences": {
    "locale": "EN"
  },
  "orcid-identifier": {
    "host": "sandbox.orcid.org",
    "path": "0000-0002-1825-0097",
    "uri": "http://sandbox.orcid.org/0000-0002-1825-0097"
  }
}
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
