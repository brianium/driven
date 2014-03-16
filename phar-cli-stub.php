<?php
Phar::mapPhar();

require_once 'phar://'.__FILE__.'/ClassLoader.php';
$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Symfony', 'phar://' . __FILE__);
$loader->add('Driven', 'phar://' . __FILE__);
$loader->add('webignition', 'phar://' . __FILE__);
$loader->register();

$path = 'phar://' . __FILE__ .'/config';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);

use Driven\Console\DrivenApplication;

$app = new DrivenApplication();
$app->run();

__HALT_COMPILER();