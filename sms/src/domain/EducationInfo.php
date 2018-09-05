<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of EducationInfo
 *
 * @author root
 */
class EducationInfo {
    private $veducation_info,$vtrainer_name,$vphone_num,$vaddress;
    public function getVeducation_info() {
        return $this->veducation_info;
    }

    public function getVtrainer_name() {
        return $this->vtrainer_name;
    }

    public function getVphone_num() {
        return $this->vphone_num;
    }

    public function getVaddress() {
        return $this->vaddress;
    }

    public function setVeducation_info($education_info) {
        $this->veducation_info = $education_info;
    }

    public function setVtrainer_name($vtrainer_name) {
        $this->vtrainer_name = $vtrainer_name;
    }

    public function setVphone_num($vphone_num) {
        $this->vphone_num = $vphone_num;
    }

    public function setVaddress($vaddress) {
        $this->vaddress = $vaddress;
    }

}
