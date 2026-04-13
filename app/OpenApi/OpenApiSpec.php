<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'Nepali Finance Management API',
    description: 'Basic API endpoints for users and transactions in the Nepali Finance Management System.'
)]
#[OA\Server(
    url: '/api/v1',
    description: 'Primary API server'
)]
#[OA\SecurityScheme(
    securityScheme: 'basicAuth',
    type: 'http',
    scheme: 'basic'
)]
class OpenApiSpec
{
}
