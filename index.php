<?php

/**
 * This file is part of prooph/proophessor-do.
 * (c) 2014-2018 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2018 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

//namespace Prooph\ProophessorDo;
//
//// Delegate static file requests back to the PHP built-in webserver
//if (\php_sapi_name() === 'cli-server'
//    && \is_file(__DIR__ . \parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH))
//) {
//    return false;
//}
//
////Workaround for https://github.com/prooph/proophessor-do/issues/64
//\error_reporting(E_ALL & ~E_NOTICE);

//\chdir(\dirname(__DIR__));
require 'vendor/autoload.php';
use FastRoute\RouteCollector;

//$container = require __DIR__ . '/../app/bootstrap.php';
$container = require 'config/container.php';

$dispatcher = FastRoute\simpleDispatcher(function (RouteCollector $r) {
//    $r->addRoute('GET', '/', 'SuperBlog\Controller\HomeController');
    $r->addRoute('GET', '/', 'nearly_im\App\Action\Home');
    $r->addRoute('GET', '/UserTodoList', 'nearly_im\App\Action\UserTodoList');
    $r->addRoute('GET', '/article/{id}', ['SuperBlog\Controller\ArticleController', 'show']);
});

$container->get('nearly_im\App\Action\UserTodoList', '');

$route = $dispatcher->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

switch ($route[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        echo '404 Not Found';
        break;

    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        echo '405 Method Not Allowed';
        break;

    case FastRoute\Dispatcher::FOUND:
        $controller = $route[1];
        $parameters = $route[2];

        // We could do $container->get($controller) but $container->call()
        // does that automatically
        $container->call($controller, $parameters);
        break;
}

(function () {
    /** @var \Psr\Container\ContainerInterface $container */
    $container = require 'config/container.php';
//    /** @var \Zend\Expressive\Application $app */
//    $app = $container->get(\Zend\Expressive\Application::class);
//    $factory = $container->get(\Zend\Expressive\MiddlewareFactory::class);
    // Execute programmatic/declarative middleware pipeline and routing
    // configuration statements
//    (require 'config/pipeline.php')($app, $factory, $container);
//    (require 'config/routes.php')($app, $factory, $container);
//    (require 'config/event_store_http_api.php')($app, $factory, $container);
//    $app->run();
})();
