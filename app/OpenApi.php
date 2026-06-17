<?php

namespace App;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    title: 'UpFlow - Regulação de Leitos API',
    description: 'API REST para gerenciamento e controle de leitos hospitalares desenvolvida em Laravel.'
)]
#[OA\Server(
    url: 'http://127.0.0.1:8000',
    description: 'Ambiente Local'
)]
class OpenApi {}
