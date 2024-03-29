<?php

use Phalcon\Loader;
use Phalcon\Di\FactoryDefault;
use Phalcon\Mvc\View;
use Phalcon\Mvc\Url as UrlProvider;
use Phalcon\Mvc\Application;
use Phalcon\Config\Adapter\Ini as ConfigIni;
use Phalcon\Db\Adapter\Pdo\Mysql as DbAdapter;
use Phalcon\Mvc\Router;
use Phalcon\Flash\Direct as FlashDirect;
use Phalcon\Flash\Session as FlashSession;
use Phalcon\Mvc\Dispatcher;

// Define some absolute path constants to aid in locating resources
define('BASE_PATH', dirname(__DIR__));
define('APP_PATH', BASE_PATH . '/');

session_start();

$loader = new Loader();
// Create a DI
$di = new FactoryDefault();
// Create the router
$router = new Router();

$loader->registerNamespaces([
    'App\\Services'    => APP_PATH . 'services/',
    'App\\Controllers' => APP_PATH . 'controllers/',
    'App\\Models'      => APP_PATH . 'models/',
    'App\\Libs'        => APP_PATH . 'libs/',
    'App'              => APP_PATH
]);

$loader->register();

// Set config
$di->set('config', function () {
    return new ConfigIni('../config/app.ini');
}, true);

// Registering a dispatcher
$di->set(
    'dispatcher',
    function () {
        $dispatcher = new Dispatcher();

        $dispatcher->setDefaultNamespace(
            'App\\Controllers'
        );

        return $dispatcher;
    }
);

// Setup the view component
$di->set(
    'view',
    function () {
        $view = new View();
        $view->setViewsDir(APP_PATH . 'views/');
        return $view;
    }
);

// Setup a base URI
$di->set(
    'url',
    function () {
        $url = new UrlProvider();
        $url->setBaseUri('/');
        return $url;
    }
);

$di->set(
    'router',
    function () use ($router) {
        $router->add(
            '/stats',
            [
                'namespace'  => 'App\Controllers',
                'controller' => 'stats',
                'action'     => 'index',
            ]
        );
        $router->add(
            '/calculate',
            [
                'namespace'  => 'App\Controllers',
                'controller' => 'rate',
                'action'     => 'calculate',
            ]
        );

        return $router;
    }
);

$di->set(
    'db',
    function () use ($di) {
        $config = $di->get('config');
        return new DbAdapter(
            [
                'host'     => $config->database->host,
                'username' => $config->database->username,
                'password' => $config->database->password,
                'dbname'   => $config->database->dbname,
                'options'  => [
                    \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4, time_zone = '+00:00'"
                ]
            ]
        );
    }
);

// Set up the flash service
$di->set(
    'flash',
    function () {
        return new FlashDirect();
    }
);

// Set up the flash session service
$di->set(
    'flashSession',
    function () {
        return new FlashSession([
            'success' => 'alert alert-success',
            'error'   => 'alert alert-danger',
        ]);
    }
);

$application = new Application($di);

try {
    // Handle the request
    $response = $application->handle();

    $response->send();
} catch (\Exception $e) {
    echo 'Exception: ', $e->getMessage();
}