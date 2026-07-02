<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-based extension "de_slash" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace B13\DeSlash\Service;

use TYPO3\CMS\Core\Http\Uri;

class UriCleaner
{
    /**
     * Remove trailing slash from the path segment of a URL, preserving query parameters and fragments.
     * Returns the modified URL, or the original if no trailing slash in path.
     */
    public function removeTrailingSlashFromPath(string $url): string
    {
        if ($url === '') {
            return $url;
        }

        try {
            $uri = new Uri($url);
        } catch (\InvalidArgumentException $e) {
            // Malformed URL according to PSR-7 (e.g. '///'), return as is
            return $url;
        }

        $path = $uri->getPath();

        if ($path === '' || trim($path, '/') === '' || !str_ends_with($path, '/')) {
            return $url;
        }

        return (string)$uri->withPath(rtrim($path, '/'));
    }
}
