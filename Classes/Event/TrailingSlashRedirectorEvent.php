<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-based extension "de_slash" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace B13\DeSlash\Event;

use Psr\Http\Message\ServerRequestInterface;

final class TrailingSlashRedirectorEvent
{
    public bool $skipRedirect = false;

    public function __construct(public readonly ServerRequestInterface $request) {}
}
