<?php
/**
 * WhoisLookup library (https://github.com/tomphp/WhiosLookup)
 *
 * @link https://github.com/tomphp/BasicSocket for the canonical source repository
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License, version 3 (GPL-3.0)
 */

if (!($loader = @include_once __DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

/* var $loader \Composer\Autoload\ClassLoader */
$loader->add('src\\', __DIR__);
