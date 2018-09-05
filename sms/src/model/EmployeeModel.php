<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use sms\domain\Employee;

/**
 * Description of EmployeeModel
 *
 * @author Imole akpobome <imole.akpobome@gmail.com>
 * @copyright (c) 2018, Imole Akpobome
 */
class EmployeeModel extends AbstractModel {

    //put your code here
    const CLASSNAME = "\sms\domain\Employee";
    const INSRTSTMT = "INSERT INTO hr.employee (vemployee, vfirstname, vothername, vlastname, vaddress_one, vaddress_two, vstateoforigin, vcountry, ddateofemp, vdepartment, vposition,vpicture) 
	VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)";

    /**
     * @name persist
     * @param Employee $employee
     * @return bool
     */
    public function persist(Employee $employee): bool {
        $stmt = $this->db->prepare(self::INSRTSTMT);
        $stmt->bindParam(1, $employee->getVemployee());
        $stmt->bindParam(2, $employee->getVfirstname());
        $stmt->bindParam(3, $employee->getVothername());
        $stmt->bindParam(4, $employee->getVlastname());
        $stmt->bindParam(5, $employee->getVaddress_one());
        $stmt->bindParam(6, $employee->getVaddress_two());
        $stmt->bindParam(7, $employee->getVstateoforigin());
        $stmt->bindParam(8, $employee->getVcountry());
        $stmt->bindParam(9,$employee->getDdateofemp());
        $stmt->bindParam(10, $employee->getVdepartment());
        $stmt->bindParam(11, $employee->getVposition());
        $stmt->bindParam(12, $employee->getVpicture());

        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());
            return false;
        } else {
            return true;
        }
    }
    
    public function searchEmployee(string $vemployee = "", string $vfirstname = "", string $vsurname = "") : array {
        if (!empty($vemployee)) {
            try {
                $stmt = $this->db->prepare("select * from searchEmp(?)");
                $stmt->bindParam(1, $vemployee);
                if(!$stmt->execute()) {
                    print_r($stmt->errorInfo());
                }
                else{
                    return $stmt->fetchAll();
                }
            } catch (\Exception $ex) {
                
            }
        }
        else {
            try {
                $stmt = $this->db->prepare("select * from searchEmp(?,?)");
                $stmt->bindParam(1, $vfirstname);
                $stmt->bindParam(2,$vsurname);
                if(!$stmt->execute()) {
                    print_r($stmt->errorInfo());
                }
                else{
                    return $stmt->fetchAll();
                }
            } catch (\Exception $ex) {
                
            }
        }
    }
    
    public function update(string $empno, string $addr1,string $addr2) {
        $this->db->beginTransaction();
        $stmt=$this->db->prepare("select * from updateEmpAddress('$empno','$addr1','$addr2')");
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            $this->db->rollback();
        }
//        $stmt = $this->db->prepare("select * from updatestudent('$vstudent','$c_addr','$c_class')");
//        if(!$stmt->execute()){
//            print_r($stmt->errorInfo());
//            $this->db->rollback();
//        }
        $this->db->commit();
    }
    

}
