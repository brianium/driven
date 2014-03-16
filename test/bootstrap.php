<?php
if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

require_once dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';

define('BOOTSTRAP', __FILE__);

$path = dirname(__DIR__) . DS . 'config';
set_include_path(get_include_path() . PATH_SEPARATOR . $path);