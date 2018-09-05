<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Attendance
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class Attendance {
    private $vattreg;
    private $dstartdate;
    private $denddate;
    
    public function getVattreg() {
        return $this->vattreg;
    }

    public function getDstartdate() {
        return $this->dstartdate;
    }

    public function getDenddate() {
        return $this->denddate;
    }

    public function setVattreg($vattreg) {
        $this->vattreg = $vattreg;
    }

    public function setDstartdate($dstartdate) {
        $this->dstartdate = $dstartdate;
    }

    public function setDenddate($denddate) {
        $this->denddate = $denddate;
    }


    
    
}
