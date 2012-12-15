<?php
if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

error_reporting(E_ALL);
ini_set('display_errors', '1');

require_once dirname(__DIR__) . DS . 'vendor' . DS . 'autoload.php';

define('BOOTSTRAP', __FILE__);