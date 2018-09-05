<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of MedicalInfo
 *
 * @author root
 */
class MedicalInfo {
    //put your code here
    private $vmedical_info,$vconditions;
    public function getVmedical_info() {
        return $this->vmedical_info;
    }

    public function getVconditions() {
        return $this->vconditions;
    }

    public function setVmedical_info($vmedical_info) {
        $this->vmedical_info = $vmedical_info;
    }

    public function setVconditions($vconditions) {
        $this->vconditions = $vconditions;
    }



    
}
