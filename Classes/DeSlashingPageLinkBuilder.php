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
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Frontend\Event\AfterLinkIsGeneratedEvent;
use TYPO3\CMS\Frontend\Typolink\LinkResultInterface;

/**
 * Always generate links to pages without a trailing slash
 */
final class DeSlashingPageLinkBuilder
{
    public function __construct(private readonly UriCleaner $uriCleaner) {}

    public function __invoke(AfterLinkIsGeneratedEvent $event): void
    {
        /** @var LinkResultInterface $linkResult */
        $linkResult = $event->getLinkResult();
        if ($linkResult->getUrl() === '/') {
            return;
        }
        if ($linkResult->getType() === LinkService::TYPE_PAGE) {
            $url = $linkResult->getUrl();
            $newUrl = $this->uriCleaner->removeTrailingSlashFromPath($url);
            if ($newUrl !== $url) {
                // Note: LinkResultInterface does not provide a withUrl() or equivalent method to update the internal URL.
                // Downstream code calling LinkResult::getUrl() will still receive the old URL.
                // We update the 'href' attribute which is used when rendering the actual HTML anchor tag.
                $linkResult = $linkResult->withAttribute('href', $newUrl);
                $event->setLinkResult($linkResult);
            }
        }
    }
}
