<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-based extension "de_slash" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace B13\DeSlash\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use TYPO3\CMS\Core\Http\RedirectResponse;
use TYPO3\CMS\Core\Routing\PageArguments;
use TYPO3\CMS\Core\Site\Entity\Site;

/**
 * Ensure that all incoming GET requests with a trailing slash get redirected to their equivalent URL
 * without the trailing slash.
 */
class TrailingSlashRedirector implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $site = $request->getAttribute('site');

        if ($request->getMethod() !== 'GET' && $request->getMethod() !== 'HEAD') {
            return $handler->handle($request);
        }

        if (!$site instanceof Site) {
            return $handler->handle($request);
        }

        $uri = $request->getUri();
        // No slash at the very end of the URL, so let's continue
        if (!str_ends_with($uri->getPath(), '/') || $uri->getPath() === '/') {
            return $handler->handle($request);
        }

        if ($this->siteHasSlashConfiguredForCurrentPageType($site, $request->getAttribute('routing'))) {
            return $handler->handle($request);
        }

        return new RedirectResponse($uri->withPath(rtrim($uri->getPath(), '/')), 301);
    }

    private function siteHasSlashConfiguredForCurrentPageType(Site $site, ?PageArguments $pageArguments): bool
    {
        if (!$pageArguments instanceof PageArguments) {
            return false;
        }

        $pageType = $pageArguments['pageType'] ?? null;
        if ($pageType === null) {
            return false;
        }
        $routeEnhancers = $site->getConfiguration()['routeEnhancers'] ?? null;
        if ($routeEnhancers === null) {
            return false;
        }

        $pageTypeSuffix = $routeEnhancers['PageTypeSuffix'] ?? null;
        if ($pageTypeSuffix === null) {
            return false;
        }

        $map = $pageTypeSuffix['map'] ?? null;
        if ($map === null) {
            return false;
        }

        return (array_flip($map)[(int)$pageType] ?? null) === '/' || (array_flip($map)[$pageType] ?? null) === '/';
    }
}
