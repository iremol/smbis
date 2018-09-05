<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Application
 *
 * @author root
 */
class Application {
    private $vapplication,$vbio_info,$vguardian_info,$veducation_info,$vmedical_info,$vsibling,$ddateofapplication;
    public function getVapplication() {
        return $this->vapplication;
    }

    public function getVbio_info() {
        return $this->vbio_info;
    }

    public function getVguardian_info() {
        return $this->vguardian_info;
    }

    public function getVeducation_info() {
        return $this->veducation_info;
    }

    public function getVmedical_info() {
        return $this->vmedical_info;
    }

    public function getVsibling() {
        return $this->vsibling;
    }

    public function getDdateofapplication() {
        return $this->ddateofapplication;
    }

    public function setVapplication($vapplication) {
        $this->vapplication = $vapplication;
    }

    public function setVbio_info($vbio_info) {
        $this->vbio_info = $vbio_info;
    }

    public function setVguardian_info($vguardian_info) {
        $this->vguardian_info = $vguardian_info;
    }

    public function setVeducation_info($veducation_info) {
        $this->veducation_info = $veducation_info;
    }

    public function setVmedical_info($vmedical_info) {
        $this->vmedical_info = $vmedical_info;
    }

    public function setVsibling($vsibling) {
        $this->vsibling = $vsibling;
    }

    public function setDdateofapplication($ddateofapplication) {
        $this->ddateofapplication = $ddateofapplication;
    }


}
