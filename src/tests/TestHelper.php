<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;
use Phalcon\Loader;

ini_set("display_errors", 1);
error_reporting(E_ALL);

define("ROOT_PATH", dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/');

// Required for phalcon/incubator
include __DIR__ . "/../../vendor/autoload.php";

// Use the application autoloader to autoload the classes
// Autoload the dependencies found in composer
$loader = new Loader();

$loader->registerNamespaces([
    'App\\Services'    => APP_PATH . 'services/',
    'App\\Controllers' => APP_PATH . 'controllers/',
    'App\\Models'      => APP_PATH . 'models/',
    'App\\Libs'        => APP_PATH . 'libs/',
    'App'              => APP_PATH,
    'Tests'            => __DIR__
]);

$loader->register();

$di = new FactoryDefault();

Di::reset();

// Add any needed services to the DI here

Di::setDefault($di);