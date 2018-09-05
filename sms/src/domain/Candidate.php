<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Candidate
 *
 * @author root
 */
class Candidate {

    private $vcandidate, $vapplication, $vusername, $vadmission_status;

    public function getVcandidate() {
        return $this->vcandidate;
    }

    public function getVapplication() {
        return $this->vapplication;
    }

    public function getVadmission_status() {
        return $this->vadmission_status;
    }

    public function setVadmission_status($vadmission_status) {
        $this->vadmission_status = $vadmission_status;
    }

    public function getVusername() {
        return $this->vusername;
    }

    public function setVcandidate($vcandidate) {
        $this->vcandidate = $vcandidate;
    }

    public function setVapplication($vapplication) {
        $this->vapplication = $vapplication;
    }

    public function setVusername($vusername) {
        $this->vusername = $vusername;
    }

}
