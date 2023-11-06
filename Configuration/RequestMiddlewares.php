<?php

return [
    'frontend' => [
        'b13/de-slash/trailing-slash-redirector' => [
            'target' => \B13\DeSlash\Middleware\TrailingSlashRedirector::class,
            'after' => [
                'typo3/cms-frontend/page-resolver',
            ],
            'before' => [
                'typo3/cms-frontend/prepare-tsfe-rendering',
            ],
        ],
    ],
];
