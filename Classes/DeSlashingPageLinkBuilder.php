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

use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Event\AfterLinkIsGeneratedEvent;
use TYPO3\CMS\Frontend\Typolink\LinkResultInterface;

/**
 * Always generate links to pages without a trailing slash
 */
final class DeSlashingPageLinkBuilder
{
    public function __invoke(AfterLinkIsGeneratedEvent $event): void
    {
        /** @var LinkResultInterface $linkResult */
        $linkResult = $event->getLinkResult();
        if ($linkResult->getUrl() === '/') {
            return;
        }
        if ($linkResult->getType() === LinkService::TYPE_PAGE && str_ends_with($linkResult->getUrl(), '/')) {
            $linkResult = $linkResult->withAttribute('href', rtrim($linkResult->getUrl(), '/'));
            $event->setLinkResult($linkResult);
        }
    }

    /**
     * TYPO3 v11 compatibility.
     *
     * $_params = [
     *   'conf' => &$conf,
     *   'originalLinktxt' => &$linkText,
     *   'linktxt' => &$lastTypoLinkText,
     *   'finalTag' => &$finalAnchorTag,
     *   'finalTagParts' => &$finalTagParts,
     *   'linkDetails' => &$linkDetails,
     *   'tagAttributes' => &$finalTagAttributes,
     * ];
     */
    public function typolinkPostProc(array &$params, ContentObjectRenderer $cObj): void
    {
        if ($params['linkDetails']['type'] !== LinkService::TYPE_PAGE) {
            return;
        }
        if ($cObj->lastTypoLinkResult->getUrl() === '/') {
            return;
        }
        if (str_ends_with($cObj->lastTypoLinkResult->getUrl(), '/')) {
            $cObj->lastTypoLinkResult = $cObj->lastTypoLinkResult->withAttribute('href', rtrim($cObj->lastTypoLinkResult->getUrl(), '/'));
            $cObj->lastTypoLinkUrl = rtrim($cObj->lastTypoLinkUrl, '/');
            $params['finalTag'] = preg_replace('/href="(.*)\/"/', 'href="$1"', $params['finalTag']);
        }
    }
}
