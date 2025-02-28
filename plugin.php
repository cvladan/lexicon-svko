<?php

/**
 * Plugin Name:     Lexicon | SVKO
 * Plugin URI:      https://svko.com/
 * Description:     Allows you to create your glossary, encyclopedia, lexicon, wiki, dictionary or knowledge base.
 * Version:         1.0.0
 * Author:          Vladan Colovic, Dennis Hoppe
 * Author URI:      https://svko.com/
 * License:         GPL-2.0-or-later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:     lexicon
 * Domain Path:     /languages
 * Update URI:      false
 * Requires PHP:    8.0
 */

namespace SVKO\Lexicon;

defined('ABSPATH') || exit;
PHP_VERSION_ID < 80000 && die("PHP 8.0+ required. Your version: {$PHP_VERSION}");

if (!defined($_ = __NAMESPACE__)) : define($_, 1); # prevent redeclaration; singleton; don't forget endif

  foreach (glob(realpath(__DIR__ . '/includes') . '/*.php') as $inc) {
    require_once $inc;
  }

  Core::init(__FILE__);

endif;
