<?php

namespace Capisso\Core\Controllers;

class IndexController extends \Phalcon\Mvc\Controller
{

    public function indexAction()
    {
        $this->view->name = 'test';
    }

}