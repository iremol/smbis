<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Countries
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class Countries {
    //put your code here
    private $vname;
    private $id;
    private $vcode;
    
    public function getVname() {
        return $this->vname;
    }

    public function setVname($vname) {
        $this->vname = $vname;
    }

    public function getId() {
        return $this->id;
    }

    public function getVcode() {
        return $this->vcode;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function setVcode($vcode) {
        $this->vcode = $vcode;
    }



}
