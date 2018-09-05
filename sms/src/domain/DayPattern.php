<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of DayPattern
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class DayPattern {
    private $ssdaypattern;
    private $vshortdesc;
    private $vdesc;
    
    public function getSsdaypattern() {
        return $this->ssdaypattern;
    }

    public function getVshortdesc() {
        return $this->vshortdesc;
    }

    public function getVdesc() {
        return $this->vdesc;
    }

    public function setSsdaypattern($ssdaypattern) {
        $this->ssdaypattern = $ssdaypattern;
    }

    public function setVshortdesc($vshortdesc) {
        $this->vshortdesc = $vshortdesc;
    }

    public function setVdesc($vdesc) {
        $this->vdesc = $vdesc;
    }

 
            
}
