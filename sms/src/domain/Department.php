<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Department
 *
 * @author Imole Akpobome
 */
class Department {
    private $vdepartment, $vdescription;
    public function getVdepartment() {
        return $this->vdepartment;
    }

    public function getVdescription() {
        return $this->vdescription;
    }

    public function setVdepartment($vdepartment) {
        $this->vdepartment = $vdepartment;
    }

    public function setVdescription($vdescription) {
        $this->vdescription = $vdescription;
    }


}
