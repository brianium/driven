<?php
$buildDir = realpath(dirname(__FILE__)) . '/build';

$pharName = "$buildDir/driven.phar";

if (!file_exists($buildDir)) {
    mkdir($buildDir);
}

if (file_exists($pharName)) {
    unlink($pharName);
}

$p = new Phar($pharName);
$p->CompressFiles(Phar::GZ);
$p->setSignatureAlgorithm(Phar::SHA1);

$p->startBuffering();

$dirs = array(
    __DIR__ . '/src'                                               =>  '/Driven|resources/',
    __DIR__ . '/vendor/symfony/console'                            =>  '/Symfony/',
    __DIR__ . '/vendor/symfony/yaml'                               =>  '/Symfony/',
    __DIR__ . '/vendor/webignition/json-pretty-print/src'          =>  '/webignition/'
);

$p->addFile('config/composer.php');
$p->addFile('vendor/composer/ClassLoader.php', 'ClassLoader.php');

foreach ($dirs as $dir => $filter) {
    $p->buildFromDirectory($dir, $filter);
}

$p->stopBuffering();

$p->setStub(file_get_contents('phar-cli-stub.php'));