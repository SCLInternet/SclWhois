<?php
/**
 * WhoisLookup library (https://github.com/tomphp/WhiosLookup)
 *
 * @link https://github.com/tomphp/BasicSocket for the canonical source repository
 * @license http://opensource.org/licenses/MIT The MIT License (MIT)
 */

if (!($loader = @include_once __DIR__ . '/../vendor/autoload.php')) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

/* var $loader \Composer\Autoload\ClassLoader */
$loader->add('WhoisLookup\\', __DIR__ . '/src');
