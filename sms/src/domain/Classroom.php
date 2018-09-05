<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Classroom
 *
 * @author root
 */
class Classroom {
    //put your code here
    private  $vclassroom, $vshortdescription;
    public function getVclassroom() {
        return $this->vclassroom;
    }

    public function getVshortdescription() {
        return $this->vshortdescription;
    }

    public function setVclassroom($vclassroom) {
        $this->vclassroom = $vclassroom;
    }

    public function setVshortdescription($vshortdescription) {
        $this->vshortdescription = $vshortdescription;
    }


}
