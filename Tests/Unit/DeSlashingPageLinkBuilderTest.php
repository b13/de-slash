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

use B13\DeSlash\DeSlashingPageLinkBuilder;
use B13\DeSlash\Service\UriCleaner;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\LinkHandling\LinkService;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;
use TYPO3\CMS\Frontend\Event\AfterLinkIsGeneratedEvent;
use TYPO3\CMS\Frontend\Typolink\LinkResultInterface;

class DeSlashingPageLinkBuilderTest extends TestCase
{
    public function testInvokeCleansLinkWhenTypeIsPageAndTrailingSlashIsPresent(): void
    {
        $linkResult = $this->createMock(LinkResultInterface::class);
        $linkResult->method('getUrl')->willReturn('/en/');
        $linkResult->method('getType')->willReturn(LinkService::TYPE_PAGE);

        $modifiedLinkResult = self::createStub(LinkResultInterface::class);
        $linkResult->expects(self::once())
            ->method('withAttribute')
            ->with('href', '/en')
            ->willReturn($modifiedLinkResult);

        $event = new AfterLinkIsGeneratedEvent(
            $linkResult,
            self::createStub(ContentObjectRenderer::class),
            []
        );

        $uriCleaner = $this->createMock(UriCleaner::class);
        $uriCleaner->expects(self::once())
            ->method('removeTrailingSlashFromPath')
            ->with('/en/')
            ->willReturn('/en');

        $listener = new DeSlashingPageLinkBuilder($uriCleaner);
        $listener($event);

        self::assertSame($modifiedLinkResult, $event->getLinkResult());
    }

    public function testInvokeDoesNothingForHomepageRoot(): void
    {
        $linkResult = self::createStub(LinkResultInterface::class);
        $linkResult->method('getUrl')->willReturn('/');

        $event = new AfterLinkIsGeneratedEvent(
            $linkResult,
            self::createStub(ContentObjectRenderer::class),
            []
        );

        $uriCleaner = $this->createMock(UriCleaner::class);
        $uriCleaner->expects(self::never())
            ->method('removeTrailingSlashFromPath');

        $listener = new DeSlashingPageLinkBuilder($uriCleaner);
        $listener($event);

        self::assertSame($linkResult, $event->getLinkResult());
    }

    public function testInvokeDoesNothingForNonPageLinks(): void
    {
        $linkResult = self::createStub(LinkResultInterface::class);
        $linkResult->method('getUrl')->willReturn('/some-file.pdf');
        $linkResult->method('getType')->willReturn('file');

        $event = new AfterLinkIsGeneratedEvent(
            $linkResult,
            self::createStub(ContentObjectRenderer::class),
            []
        );

        $uriCleaner = $this->createMock(UriCleaner::class);
        $uriCleaner->expects(self::never())
            ->method('removeTrailingSlashFromPath');

        $listener = new DeSlashingPageLinkBuilder($uriCleaner);
        $listener($event);

        self::assertSame($linkResult, $event->getLinkResult());
    }
}
