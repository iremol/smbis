<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\core\Request;
use sms\util\DependencyInjector;
/**
 * Description of AbstractController
 *
 * @author root
 */
abstract class AbstractController {

    protected $request;
    protected $db;
    protected $config;
    protected $view;
    protected $log;
    protected $customerId;
    protected $di;

    public function __construct(DependencyInjector $di, Request $request) {
        $this->request = $request;
        $this->di = $di;

        $this->db = $di->get('PDO');
        $this->log = $di->get('Logger');
        $this->view = $di->get('Twig_Environment');
        $this->config = $di->get('Utils\Config');
    }

    public function setCustomerId(int $customerId) {
        $this->customerId = $customerId;
    }

    protected function render(string $template, array $params): string {
        return $this->view->loadTemplate($template)->render($params);
         //return $this->view->render($template,$params);
    }

    /**
     * @method sessionEnforcer
     * @param AbstractController $controller
     * @param string $sessionVar
     * @param string $returnUrl
     * @return string
     * Checks for a valid session and allows a user to continue.
     */
    protected function sessionEnforcer(AbstractController $controller, $sessionVar , string $returnUrl) : string {
        //session_start(); // continues the existing session
        if(!isset($_SESSION[$sessionVar])){ //checks if the variable is not empty
            return $controller->render($returnUrl, []); // if it is empty it returns to this url
        }
    }
}
