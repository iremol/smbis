<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Class
 *
 * @author root
 */
class Class_ {
    //put your code here
    private $vclass,$vclassteacher, $vclassname, $vclassroom,$bsid, $vattreg,$ssdaypattern ;
    
    public function getBsid() {
        return $this->bsid;
    }

    public function getVattreg() {
        return $this->vattreg;
    }

    public function getSsdaypattern() {
        return $this->ssdaypattern;
    }

    public function setBsid($bsid) {
        $this->bsid = $bsid;
    }

    public function setVattreg($vattreg) {
        $this->vattreg = $vattreg;
    }

    public function setSsdaypattern($ssdaypattern) {
        $this->ssdaypattern = $ssdaypattern;
    }

        public function getVclass() {
        return $this->vclass;
    }

    public function getVclassteacher() {
        return $this->vclassteacher;
    }

    public function getVclassname() {
        return $this->vclassname;
    }

    public function getVclassroom() {
        return $this->vclassroom;
    }

    public function setVclass($vclass) {
        $this->vclass = $vclass;
    }

    public function setVclassteacher($vclassteacher) {
        $this->vclassteacher = $vclassteacher;
    }

    public function setVclassname($vclassname) {
        $this->vclassname = $vclassname;
    }

    public function setVclassroom($vclassroom) {
        $this->vclassroom = $vclassroom;
    }


}
