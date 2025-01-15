<?php
/*
 * This file is part of TYPO3 CMS-based extension "de_slash" by b13.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'De-Slash URLs',
    'description' => 'In case you do not want to havea trailing slash in your URLs, this extension will remove it.',
    'category' => 'fe',
    'author' => 'Benjamin Mack',
    'author_email' => 'typo3@b13.com',
    'state' => 'stable',
    'author_company' => 'b13 GmbH',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.0.0-13.4.99',
        ],
        'conflicts' => [],
        'suggests' => [],
    ],
];
