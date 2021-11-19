<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;


class OpenApiFactory implements OpenApiFactoryInterface
{
  public function __construct(private OpenApiFactoryInterface $decorated)
  {
  }

  public function __invoke(array $context = []): OpenApi
  {
    // var_dump($context);
    $openApi = $this->decorated->__invoke($context);
    /** @var PathItem $path */
    foreach ($openApi->getPaths()->getPaths() as $key => $path) {
      if ($path->getGet() && $path->getGet()->getSummary() === 'hidden') {
        $openApi->getPaths()->addPath($key, $path->withGet(null));
      }
    }

    $schemas = $openApi->getComponents()->getSchemas();
    $securitySchemas = $openApi->getComponents()->getSecuritySchemes();


    // SCHEMA JWT
    $securitySchemas['bearerAuth'] = new \ArrayObject([
      'type' => 'http',
      'scheme' => 'bearer',
      'bearerFormat' => 'JWT',
      'description' => 'Token JWT',
    ]);

    // SCHEMA CREDENTIALS
    $schemas['Credentials'] = new \ArrayObject([
      'type' => 'object',
      'properties' => [
        'username' => [
          'type' => 'string',
          'example' => 'john@doe.fr',
        ],
        'password' => [
          'type' => 'string',
          'example' => '0000'
        ]
      ]
    ]);

    // SCHEMA REGISTRATION
    $schemas['Registration'] = new \ArrayObject([
      'type' => 'object',
      'properties' => [
        'email' => [
          'type' => 'string',
          'example' => 'john@doe.fr',
        ],
        'username' => [
          'type' => 'string',
          'example' => 'john',
        ],
        'password' => [
          'type' => 'string',
          'example' => '0000'
        ]
      ]
    ]);

    // SCHEMA RESPONSE AUHT
    $schemas['AuthJWT'] = new \ArrayObject([
      'type' => 'object',
      'properties' => [
        'token' => [
          'type' => 'string',
          'example' => 'TOKEN',
        ],
      ]
    ]);


    // ROUTE /api/login
    $pathItem = new PathItem(
      post: new Operation(
        operationId: 'postApiLogin',
        tags: ['Auth'],
        requestBody: new RequestBody(
          content: new \ArrayObject([
            'application/json' => [
              'schema' => [
                '$ref' => '#/components/schemas/Credentials'
              ]
            ]
          ])
        ),
        responses: [
          '200' => [
            'description' => 'Utilisateur connecté',
            'content' => [
              'application/json' => [
                'schema' => [
                  '$ref' => '#/components/schemas/User-read.User'
                ]
              ]
            ]
          ]
        ]
      )
    );

    $openApi->getPaths()->addPath('/api/login', $pathItem);

    // ROUTE /api/signup
    $pathItem = new PathItem(
      post: new Operation(
        operationId: 'postApiSignUp',
        tags: ['Auth'],
        requestBody: new RequestBody(
          content: new \ArrayObject([
            'application/json' => [
              'schema' => [
                '$ref' => '#/components/schemas/Registration'
              ]
            ]
          ])
        ),
        responses: [
          '200' => [
            'description' => 'Utilisateur connecté',
            'content' => [
              'application/json' => [
                'schema' => [
                  '$ref' => '#/components/schemas/AuthJWT'
                ]
              ]
            ]
          ]
        ]
      )
    );

    $openApi->getPaths()->addPath('/api/signup', $pathItem);



    return $openApi;
  }
}
