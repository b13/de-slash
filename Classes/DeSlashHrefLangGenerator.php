<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-based extension "de_slash" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace B13\DeSlash;

use B13\DeSlash\Service\UriCleaner;
use TYPO3\CMS\Frontend\Event\ModifyHrefLangTagsEvent;

/**
 * Ensure that no canonical tag ever has a trailing slash at the end
 */
class DeSlashHrefLangGenerator
{
    public function __construct(private readonly UriCleaner $uriCleaner) {}

    public function __invoke(ModifyHrefLangTagsEvent $event): void
    {
        $hrefLangs = $event->getHrefLangs();
        foreach ($hrefLangs as $key => $hrefLang) {
            $hrefLangs[$key] = $this->uriCleaner->removeTrailingSlashFromPath($hrefLang);
        }
        $event->setHrefLangs($hrefLangs);
    }
}
