<?php

declare(strict_types=1);

namespace Src\Http;

use Src\Http\ResourceMap;

class ResourceController
{
    public function handle(?string $resource): array
    {
        if (!$resource) {
            http_response_code(400);
            return ['error' => 'Missing resource parameter'];
        }

        $repo = ResourceMap::resolve($resource);
        return $repo->all();
    }
}
