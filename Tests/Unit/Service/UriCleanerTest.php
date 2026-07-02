<?php

declare(strict_types=1);

/*
 * This file is part of TYPO3 CMS-based extension "de_slash" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace B13\DeSlash\Tests\Unit\Service;

use B13\DeSlash\Service\UriCleaner;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class UriCleanerTest extends TestCase
{
    #[DataProvider('removeTrailingSlashFromPathDataProvider')]
    public function testRemoveTrailingSlashFromPath(string $url, string $expected): void
    {
        $uriCleaner = new UriCleaner();
        self::assertSame($expected, $uriCleaner->removeTrailingSlashFromPath($url));
    }

    public static function removeTrailingSlashFromPathDataProvider(): array
    {
        return [
            'empty path / homepage root' => [
                '/',
                '/',
            ],
            'standard path with trailing slash' => [
                '/en/',
                '/en',
            ],
            'standard path without trailing slash' => [
                '/en',
                '/en',
            ],
            'path with trailing slash and query parameters' => [
                '/en/?sitemap=pages&type=123',
                '/en?sitemap=pages&type=123',
            ],
            'path without trailing slash and query parameters' => [
                '/en?sitemap=pages&type=123',
                '/en?sitemap=pages&type=123',
            ],
            'path with trailing slash, query parameters and fragment' => [
                '/en/?sitemap=pages#anchor',
                '/en?sitemap=pages#anchor',
            ],
            'full url with trailing slash' => [
                'https://example.com/en/',
                'https://example.com/en',
            ],
            'full url with trailing slash and query' => [
                'https://example.com/en/?sitemap=pages&type=123',
                'https://example.com/en?sitemap=pages&type=123',
            ],
            'full url with trailing slash and fragment' => [
                'https://example.com/en/#anchor',
                'https://example.com/en#anchor',
            ],
            'full url with trailing slash, port, query and fragment' => [
                'https://example.com:8080/en/?sitemap=pages&type=123#anchor',
                'https://example.com:8080/en?sitemap=pages&type=123#anchor',
            ],
            'empty string' => [
                '',
                '',
            ],
            'protocol-relative url with trailing slash' => [
                '//example.com/en/',
                '//example.com/en',
            ],
            'url with user and password' => [
                'http://user:pass@example.com/en/',
                'http://user:pass@example.com/en',
            ],
            'multiple trailing slashes' => [
                '/en//',
                '/en',
            ],
            'only slashes as path' => [
                '///',
                '///',
            ],
        ];
    }
}
