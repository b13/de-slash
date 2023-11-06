<?php

defined('TYPO3') or die();

// TYPO3 v11
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['tslib/class.tslib_content.php']['typoLink_PostProc']['de-slash'] = \B13\DeSlash\DeSlashingPageLinkBuilder::class . '->typolinkPostProc';
