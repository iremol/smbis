<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use sms\domain\Department;

/**
 * Description of DepartmentModel
 *
 * @author root
 */
class DepartmentModel extends AbstractModel{
    const CLASSNAME = '\sms\domain\Department';
    const INSRTSTMT = "insert into hr.department values(:vdept,:vdesc)";
    public function persist(Department $dept) {
        $stmt  =  $this->db->prepare(self::INSRTSTMT);
        $stmt->bindParam(":vdept",$dept->getVdepartment());
        $stmt->bindParam(":vdesc",$dept->getVdescription());
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
        }
        else{
            return true;
        }
    }
    
    public function getAll() : array {
        $stmt = $this->db->prepare("select * from hr.department");
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            return null;
        }
        else{
            return $stmt->fetchAll(PDO::FETCH_CLASS,self::CLASSNAME);
        }
    }
}
