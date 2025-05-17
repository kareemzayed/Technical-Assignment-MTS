<?php

declare(strict_types=1);

namespace App\Http;

use App\Http\ResourceMap;

/**
 * Handles HTTP resource requests and delegates data fetching to the appropriate repository.
 */
class ResourceController
{
    /**
     * Handle the request for a specific resource.
     *
     * @param string|null $resource The requested resource name.
     * @return array Response data or an error message.
     */
    public function handle(?string $resource): array
    {
        // Return a 400 Bad Request if the resource parameter is missing
        if (!$resource) {
            http_response_code(400);
            return [
                'error' => 'Missing resource parameter'
            ];
        }

        // Resolve the repository corresponding to the resource name
        $repo = ResourceMap::resolve($resource);

        // Return all data from the resolved repository
        return $repo->all();
    }
}
