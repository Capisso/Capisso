<?php

error_reporting(E_ALL);

class Application extends \Phalcon\Mvc\Application
{

    /**
     * Register the services here to make them general or register in the ModuleDefinition to make them module-specific
     */
    protected function _registerServices()
    {

        $di = new \Phalcon\DI\FactoryDefault();

        $loader = new \Phalcon\Loader();

        /**
         * We're a registering a set of directories taken from the configuration file
         */
        $loader->registerDirs(
            array(
                //__DIR__ . '/../apps/library/'
            )
        )->register();

        //Registering a router
        $di->set('router', function(){

            $router = new \Phalcon\Mvc\Router();

            $router->setDefaultModule("core");


            return $router;

        });

        $this->setDI($di);
    }

    public function main()
    {

        $this->_registerServices();

        //Register the installed modules
        $this->registerModules(array(
            'core' => array(
                'className' => 'Capisso\Core\Module',
                'path' => '../apps/core/Module.php'
            ),
            'customer-service' => array(
                'className' => 'Capisso\CustomerService\Module',
                'path' => '../apps/customer-service/Module.php'
            ),
            'node-manager' => array(
                'className' => 'Capisso\NodeManager\Module',
                'path' => '../apps/node-manager/Module.php'
            ),
        ));

        echo $this->handle()->getContent();
    }

}

$application = new Application();
$application->main();