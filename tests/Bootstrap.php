<?php

if  (
    !($loader = @include __DIR__ . '/../vendor/autoload.php')
    && !($loader = @include __DIR__ . '/../../../autoload.php')
) {
    throw new RuntimeException('vendor/autoload.php could not be found. Did you run `php composer.phar install`?');
}

/* @var $loader \Composer\Autoload\ClassLoader */
$loader->add('KJSenchaTest\\', __DIR__);
$loader->add('KJSenchaTestAsset\\', __DIR__);

if (!$config = @include __DIR__ . '/TestConfiguration.php') {
    $config = require __DIR__ . '/TestConfiguration.php.dist';
}

\KJSenchaTest\Util\ServiceManagerFactory::setConfig($config);
