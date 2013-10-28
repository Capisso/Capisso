<?php

namespace Capisso\Core;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface,
    Phalcon\Mvc\View\Engine\Volt,
    Phalcon\Config\Adapter\Ini;

class Module implements ModuleDefinitionInterface
{

    /**
     * Register a specific autoloader for the module
     */
    public function registerAutoloaders()
    {

        $loader = new Loader();

        $loader->registerNamespaces(
            array(
                'Capisso\Core\Controllers' => '../apps/core/controllers/',
                'Capisso\Core\Models' => '../apps/core/models/',
            )
        );

        $loader->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {
        $config = new Ini(__DIR__.'/config/app.ini');

        //Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace("Capisso\\Core\\Controllers");
            return $dispatcher;
        });

        //Register Volt as a service
        $di->set('voltService', function($view, $di) {

            $volt = new Volt($view, $di);

            $volt->setOptions(array(
                "compiledPath" => "../apps/storage/compiled-views/",
                "compiledExtension" => ".compiled"
            ));

            return $volt;
        });

        //Registering the view component
        $di->set('view', function () {
            $view = new View();

            $view->setLayoutsDir('../apps/core/views/layouts/');
            $view->setViewsDir('../apps/core/views/');
            $view->setTemplateAfter('main');

            $view->registerEngines(array(
                ".phtml" => 'voltService'
            ));

            return $view;
        });

        $di->set('db', function() use($config) {
            return new \Phalcon\Db\Adapter\Pdo\Mysql(array(
                "host" => $config->database->host,
                "username" => $config->database->username,
                "password" => $config->database->password,
                "dbname" => $config->database->name
            ));
        });
    }

}