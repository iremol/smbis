<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Student
 *
 * @author root
 */
class Student {
    //put your code here
    private $vstudent, $vapplication, $vclass;
    public function getVstudent() {
        return $this->vstudent;
    }

    public function getVapplication() {
        return $this->vapplication;
    }

    public function getVclass() {
        return $this->vclass;
    }

    public function setVstudent($vstudent) {
        $this->vstudent = $vstudent;
    }

    public function setVapplication($vapplication) {
        $this->vapplication = $vapplication;
    }

    public function setVclass($vclass) {
        $this->vclass = $vclass;
    }


}
