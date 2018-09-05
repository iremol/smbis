<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Position
 *
 * @author Imole Akpobome
 * @since 1.0
 */
class Position {
    private $vposition;
    private $vdescription;
    
    public function getVposition() {
        return $this->vposition;
    }

    public function getVdescription() {
        return $this->vdescription;
    }

    public function setVposition($vposition) {
        $this->vposition = $vposition;
    }

    public function setVdescription($vdescription) {
        $this->vdescription = $vdescription;
    }


}
