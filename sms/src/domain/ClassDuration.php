<?php

namespace sms\domain;
/**
 * @author Imole Akpobome <imole.akpobome@gmail.com>
 */
/**
 * @name ClassDuration maps to the sys_class_duratn table.
 * Fully represents the duration of a class.
 */
class ClassDuration { 
 
    private $bsid; // the unique identity of each duration 
    private $siduration; // the value of the duration
    private $vdesc; // short description of the duration 

    /**
     * @method $getBsid
     * @return int
     */
    public function getBsid() : int {
        return $this->bsid;
    }

    /**
     * @access public
     * @return int
     * 
     */
    public function getSiduration():int {
        return $this->siduration;
    }

    /**
     * @access public
     * @return string
     */
    public function getVdesc(): string {
        return $this->vdesc;
    }
    /**
     * @access public
     * @param int $bsid
     */
    public function setBsid($bsid) {
        $this->bsid = $bsid;
    }
    
    /**
     * @access public
     * @param int $siduration
     */
    public function setSiduration($siduration) {
        $this->siduration = $siduration;
    }
    /**
     * @access public
     * @param string $vdesc
     */
    public function setVdesc($vdesc) {
        $this->vdesc = $vdesc;
    }


}

