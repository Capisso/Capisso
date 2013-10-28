<?php

namespace Capisso\CustomerService;

use Phalcon\Loader,
    Phalcon\Mvc\Dispatcher,
    Phalcon\Mvc\View,
    Phalcon\Mvc\ModuleDefinitionInterface,
    Phalcon\Mvc\View\Engine\Volt;

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
                'Capisso\CustomerService\Controllers' => '../apps/customer-service/controllers/',
                'Capisso\CustomerService\Models' => '../apps/customer-service/models/',
            )
        );

        $loader->register();
    }

    /**
     * Register specific services for the module
     */
    public function registerServices($di)
    {

        //Registering a dispatcher
        $di->set('dispatcher', function () {
            $dispatcher = new Dispatcher();
            $dispatcher->setDefaultNamespace("Capisso\CustomerService\Controllers");
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
            $view->setViewsDir('../apps/customer-service/views/');
            $view->setTemplateAfter('main');

            $view->registerEngines(array(
                ".phtml" => 'voltService'
            ));

            return $view;
        });
    }

}