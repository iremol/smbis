<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use sms\domain\Position;

/**
 * Description of PositionModel
 *
 * @author Imole Akpobome
 * @since 1.0
 * @
 */
class PositionModel extends AbstractModel{
    const CLASSNAME = "\sms\domain\Position";
    const INSRTSTMT = "insert into hr.position values (:pos,:desc)";
    
    /**
     * @author imole akpobome <imole.akpobome@gmail.com>
     * @version 1.0
     * @param  Position
     * @return bool
     * Inserts a new Position into the hr.position table.
     * Access is only to staff of management.
     */
    public function persist(Position $position) : bool{
        $stmt = $this->db->prepare(self::INSRTSTMT);
        $stmt->bindParam(":pos", $position->getVposition());
        $stmt->bindParam(":desc",$position->getVdescription());
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            return false;
        }
        else{
            return true;
        }
    }
    
    public function getAll() : array {
        $stmt = $this->db->prepare("select * from hr.position");
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            return null;
        }
        else{
            return $stmt->fetchAll(PDO::FETCH_CLASS,self::CLASSNAME);
        }
    }
    
    /**
     * @access public
     * @return array
     */
    public function getTeachers() : array {
        $stmt = $this->db->prepare("select * from getteachers()"); // prepares a statement
        if(!$stmt->execute()){ // test if statement execution is a success
            print_r($stmt->errorInfo()); // fails! show error message
            return null; // returns null
        }
        else {
            return $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME); //  returns a record formatted as the class it is representing
        }
    }
}
