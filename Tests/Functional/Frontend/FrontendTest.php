<?php

declare(strict_types=1);

namespace B13\DeSlash\Tests\Functional\Service;

/*
 * This file is part of TYPO3 CMS-extension listelements by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\TestingFramework\Core\Functional\Framework\Frontend\InternalRequest;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FrontendTest extends FunctionalTestCase
{
    /**
     * @var non-empty-string[]
     */
    protected array $coreExtensionsToLoad = ['core', 'frontend', 'seo'];

    /**
     * @var array<string, non-empty-string>
     */
    protected array $pathsToLinkInTestInstance = [
        'typo3conf/ext/de_slash/Build/sites' => 'typo3conf/sites',
    ];

    /**
     * @var non-empty-string[]
     */
    protected array $testExtensionsToLoad = [
        'de_slash',
    ];

    /**
     * @test
     */
    public function callPage(): void
    {
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/page.csv');
        $this->setUpFrontendRootPage(
            1,
            [
                'setup' => ['EXT:de_slash/Tests/Functional/Fixtures/TypoScript/setup.typoscript'],
            ]
        );
        $response = $this->executeFrontendSubRequest(new InternalRequest('http://localhost/'));
        $body = (string)$response->getBody();
        self::assertStringContainsString('hello world', $body);
    }
}
