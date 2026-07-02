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

use B13\DeSlash\DeSlashHrefLangGenerator;
use B13\DeSlash\Service\UriCleaner;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Frontend\Event\ModifyHrefLangTagsEvent;

class DeSlashHrefLangGeneratorTest extends TestCase
{
    public function testInvokeCleansHrefLangUrls(): void
    {
        $hrefLangs = [
            'en-US' => 'https://example.com/en/',
            'de-DE' => 'https://example.com/de',
        ];

        $event = new ModifyHrefLangTagsEvent(self::createStub(ServerRequestInterface::class));
        $event->setHrefLangs($hrefLangs);

        $uriCleaner = $this->createMock(UriCleaner::class);
        $uriCleaner->expects(self::exactly(2))
            ->method('removeTrailingSlashFromPath')
            ->willReturnMap([
                ['https://example.com/en/', 'https://example.com/en'],
                ['https://example.com/de', 'https://example.com/de'],
            ]);

        $listener = new DeSlashHrefLangGenerator($uriCleaner);
        $listener($event);

        self::assertSame([
            'en-US' => 'https://example.com/en',
            'de-DE' => 'https://example.com/de',
        ], $event->getHrefLangs());
    }
}
