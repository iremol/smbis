<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\facade;

use sms\controller\ApplicationController;

/**
 * Description of SMS
 *
 * @author root
 */
class SMS {
    //put your code here
    
    public function persistApplication($di, $request) {
      $applicationController = new ApplicationController($di, $request);
       $applicationController->persist();
       
    }
}
