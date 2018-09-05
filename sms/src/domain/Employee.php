<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\domain;

/**
 * Description of Employee
 *
 * @author Imole Akpobome <imole.akpobome@gmail.com>
 */
class Employee {

    /**
     * XXX
     *
     * @private    private char(100) $vemployee
     * @access public
     * @static      */
    private $vemployee;

    /**
     * XXX
     *
     * @private    private char(50) $vfirstname
     * @access public
     */
    private $vfirstname;

    /**
     * XXX
     *
     * @private    private char(50) $vothername
     * @access public
     */
    private $vothername;

    /**
     * XXX
     *
     * @private    private char(50) $vlastname
     * @access public
     */
    private $vlastname;

    /**
     * XXX
     *
     * @private    private char(255) $vaddress_one
     * @access public
     */
    private $vaddress_one;

    /**
     * XXX
     *
     * @private    private char(255) $vaddress_two
     * @access public
     */
    private $vaddress_two;

    /**
     * XXX
     *
     * @private    private char(20) $vstateoforigin
     * @access public
     */
    private $vstateoforigin;

    /**
     * XXX
     *
     * @private    private char(50) $vcountry
     * @access public
     */
    private $vcountry;

    /**
     * XXX
     *
     * @private    date $ddateofemp
     * @access public
     */
    private $ddateofemp;

    /**
     * XXX
     *
     * @private    private char(20) $vdepartment
     * @access public
     */
    private $vdepartment;

    /**
     * XXX
     *
     * @private    private char(20) $vposition
     * @access public
     */
    private $vposition;

    /**
     * XXX
     *
     * @private    private char(255) $vposition
     * @access public
     */
    private $vpicture;

    /**
     * @private    private char(255) $vposition
     * @access private
     * @var string 
     */
    private $email;
    public function getEmail() {
        return $this->email;
    }

    /**
     * 
     * @param string $email
     */
    public function setEmail(string $email) {
        $this->email = $email;
    }

    
    public function getVpicture() {
        return $this->vpicture;
    }

    public function setVpicture($vpicture) {
        $this->vpicture = $vpicture;
    }

    public function getVposition() {
        return $this->vposition;
    }

    public function setVposition($vposition) {
        $this->vposition = $vposition;
    }

    public function getVemployee() {
        return $this->vemployee;
    }

    public function getVfirstname() {
        return $this->vfirstname;
    }

    public function getVothername() {
        return $this->vothername;
    }

    public function getVlastname() {
        return $this->vlastname;
    }

    public function getVaddress_one() {
        return $this->vaddress_one;
    }

    public function getVaddress_two() {
        return $this->vaddress_two;
    }

    public function getVstateoforigin() {
        return $this->vstateoforigin;
    }

    public function getVcountry() {
        return $this->vcountry;
    }

    public function getDdateofemp() {
        return $this->ddateofemp;
    }

    public function getVdepartment() {
        return $this->vdepartment;
    }

    public function setVemployee($vemployee) {
        $this->vemployee = $vemployee;
    }

    public function setVfirstname($vfirstname) {
        $this->vfirstname = $vfirstname;
    }

    public function setVothername($vothername) {
        $this->vothername = $vothername;
    }

    public function setVlastname($vlastname) {
        $this->vlastname = $vlastname;
    }

    public function setVaddress_one($vaddress_one) {
        $this->vaddress_one = $vaddress_one;
    }

    public function setVaddress_two($vaddress_two) {
        $this->vaddress_two = $vaddress_two;
    }

    public function setVstateoforigin($vstateoforigin) {
        $this->vstateoforigin = $vstateoforigin;
    }

    public function setVcountry($vcountry) {
        $this->vcountry = $vcountry;
    }

    public function setDdateofemp($ddateofemp) {
        $this->ddateofemp = $ddateofemp;
    }

    public function setVdepartment($vdepartment) {
        $this->vdepartment = $vdepartment;
    }

}
