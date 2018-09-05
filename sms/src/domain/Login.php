<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Login
 *
 * @author root
 */
class Login {
    //put your code here
    private $vusername, $vpassword;
    public function getVusername() {
        return $this->vusername;
    }

    public function getVpassword() {
        return $this->vpassword;
    }

    public function setVusername($vusername) {
        $this->vusername = $vusername;
    }

    public function setVpassword($vpassword) {
        $this->vpassword = $vpassword;
    }


}
