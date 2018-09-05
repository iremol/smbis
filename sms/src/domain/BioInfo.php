<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of BioInfo
 *
 * @author Imole Akpobome
 */
class BioInfo {

    private $vbio_info, $vsurname, $vfirstname, $vothernames, $vgender, $ddob, $vaddress,$vpic;
    
    public function setParams(array $params) {
        foreach($params as $param){
        }
    }
    
    public function setVbio_info($vbio_info) {
        $this->vbio_info = $vbio_info;
    }

    public function setVsurname($vsurname) {
        $this->vsurname = $vsurname;
    }

    public function setVfirstname($vfirstname) {
        $this->vfirstname = $vfirstname;
    }

    public function setVothernames($vothernames) {
        $this->vothernames = $vothernames;
    }

    public function setVgender($vgender) {
        $this->vgender = $vgender;
    }

    public function setDdob($ddob) {
        $this->ddob = $ddob;
    }

    public function setVaddress($vaddress) {
        $this->vaddress = $vaddress;
    }

    
    public function getVbio_info() {
        return $this->vbio_info;
    }

    public function getVsurname() {
        return $this->vsurname;
    }

    public function getVfirstname() {
        return $this->vfirstname;
    }

    public function getVothernames() {
        return $this->vothernames;
    }

    public function getVgender() {
        return $this->vgender;
    }

    public function getDdob() {
        return $this->ddob;
    }

    public function getVaddress() {
        return $this->vaddress;
    }
    public function getVpic() {
        return $this->vpic;
    }

    public function setVpic($vpic) {
        $this->vpic = $vpic;
    }



}
