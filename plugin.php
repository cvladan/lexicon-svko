<?php

/**
 * Plugin Name:     Lexicon | SVKO
 * Plugin URI:      https://svko.com/
 * Description:     Patched slugs inside "post-type-labels.php" and "cross-linker.php" flags. Encyclopedia enables you to create your own encyclopedia, lexicon, glossary, wiki, dictionary or knowledge base.
 * Version:         1.7.58
 * Author:          Dennis Hoppe
 * Author URI:      https://svko.com/
 * License:         GPL-2.0-or-later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     lexicon
 * Domain Path:     /languages
 * Update URI:      false
 * Requires PHP:      8.0
 */

if (version_compare(PHP_VERSION, '7.4', '<')) {
    die(sprintf('Your PHP version (%s) is far too old. <a href="https://secure.php.net/supported-versions.php" target="_blank">Please upgrade immediately.</a> Then activate the plugin again.', PHP_VERSION));
}

$includeFiles = function ($pattern) {
    $arr_files = glob($pattern);
    if (is_Array($arr_files)) {
        foreach ($arr_files as $include_file) {
            include_once $include_file;
        }
    }
};

$includeFiles(__DIR__ . '/includes/*.php');

SVKO\Lexicon\Core::init(__FILE__);
