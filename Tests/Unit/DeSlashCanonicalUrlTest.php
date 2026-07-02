<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-based extension "de_slash" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace B13\DeSlash\Tests\Unit;

use B13\DeSlash\DeSlashCanonicalUrl;
use B13\DeSlash\Service\UriCleaner;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Domain\Page;
use TYPO3\CMS\Seo\Event\ModifyUrlForCanonicalTagEvent;

class DeSlashCanonicalUrlTest extends TestCase
{
    public function testInvokeCleansUrlWhenTrailingSlashIsPresent(): void
    {
        $event = new ModifyUrlForCanonicalTagEvent(
            self::createStub(ServerRequestInterface::class),
            new Page([]),
            'https://example.com/en/',
            null
        );

        $uriCleaner = $this->createMock(UriCleaner::class);
        $uriCleaner->expects(self::once())
            ->method('removeTrailingSlashFromPath')
            ->with('https://example.com/en/')
            ->willReturn('https://example.com/en');

        $listener = new DeSlashCanonicalUrl($uriCleaner);
        $listener($event);

        self::assertSame('https://example.com/en', $event->getUrl());
    }

    public function testInvokeDoesNotSetUrlWhenNoTrailingSlash(): void
    {
        $event = new ModifyUrlForCanonicalTagEvent(
            self::createStub(ServerRequestInterface::class),
            new Page([]),
            'https://example.com/en',
            null
        );

        $uriCleaner = $this->createMock(UriCleaner::class);
        $uriCleaner->expects(self::once())
            ->method('removeTrailingSlashFromPath')
            ->with('https://example.com/en')
            ->willReturn('https://example.com/en');

        $listener = new DeSlashCanonicalUrl($uriCleaner);
        $listener($event);

        self::assertSame('https://example.com/en', $event->getUrl());
    }
}
