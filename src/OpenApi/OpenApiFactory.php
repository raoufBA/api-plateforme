<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\Operation;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\Model\RequestBody;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);
        /** @var  PathItem $path */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && $path->getGet()->getSummary() == "hidden") {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        //add cookies
        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['cookieAuth'] = new \ArrayObject([
            'type' => 'apiKey',
            'in' => 'cookie',
            'name' => 'PHPSESSID'

        ]);
        $schemas = $openApi->getComponents()->getSchemas();
        $schemas['Credentials'] = new \ArrayObject([
            'type' => 'object',
            'properties' => [
                'username' => [
                    'type' => 'string',
                    'example' => 'raouf.belanes@gmail.com'
                ],
                'password' => [
                    'type' => 'string',
                    'example' => 'raouf'
                ]
            ],

        ]);
        $openApi = $openApi->withSecurity(['cookieAuth' => []]);
        $pathItem = new PathItem(null, null, null, null, null,
            new Operation(
                'postApiLogin',
                ['Auth'],
                [
                    '200' => [
                        'description' => 'User connected',
                        'content' => [
                            'application/ldjson' => [
                                'schema' => [
                                    '$ref' => '#/components/schemas/User-User.collection.read'
                                ]
                            ]
                        ]
                    ]
                ],
                '',
                '',
                null,
                [],
                new RequestBody('', new \ArrayObject([
                    'application/json' => [
                        'schema' => [
                            '$ref' => '#/components/schemas/Credentials'
                        ]
                    ]
                ]))

            )
        );
        $openApi->getPaths()->addPath('/api/login', $pathItem);

        $pathItem = new PathItem(null, null, null,
            new Operation(
                'postApiLogout',
                ['Auth'],
                [
                    '204' => [
                        'description' => 'User connected',
                    ]
                ],
                '',
                '',
                null,
                [],
                new RequestBody('', new \ArrayObject([]))

            )
        );
        $openApi->getPaths()->addPath('/logout', $pathItem);

        return $openApi;
    }
}