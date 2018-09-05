<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of GuardianInfo
 *
 * @author root
 */
class GuardianInfo {
    //put your code here
    private $vguardian_info,$vtitle,$vsurname,$vfirstname,$vothername,$vaddress,$vpostcode,$vemail,$vdaytime_no,$vrelationship;
    
    public function getVguardian_info() {
        return $this->vguardian_info;
    }

    public function getVtitle() {
        return $this->vtitle;
    }

    public function getVsurname() {
        return $this->vsurname;
    }

    public function getVfirstname() {
        return $this->vfirstname;
    }

    public function getVothername() {
        return $this->vothername;
    }

    public function getVaddress() {
        return $this->vaddress;
    }

    public function getVpostcode() {
        return $this->vpostcode;
    }

    public function getVemail() {
        return $this->vemail;
    }

    public function getVdaytime_no() {
        return $this->vdaytime_no;
    }

    public function getVrelationship() {
        return $this->vrelationship;
    }

    public function setVguardian_info($vguardian_info) {
        $this->vguardian_info = $vguardian_info;
    }

    public function setVtitle($vtitle) {
        $this->vtitle = $vtitle;
    }

    public function setVsurname($vsurname) {
        $this->vsurname = $vsurname;
    }

    public function setVfirstname($vfirstname) {
        $this->vfirstname = $vfirstname;
    }

    public function setVothername($vothername) {
        $this->vothername = $vothername;
    }

    public function setVaddress($vaddress) {
        $this->vaddress = $vaddress;
    }

    public function setVpostcode($vpostcode) {
        $this->vpostcode = $vpostcode;
    }

    public function setVemail($vemail) {
        $this->vemail = $vemail;
    }

    public function setVdaytime_no($vdaytime_no) {
        $this->vdaytime_no = $vdaytime_no;
    }

    public function setVrelationship($vrelationship) {
        $this->vrelationship = $vrelationship;
    }


}
