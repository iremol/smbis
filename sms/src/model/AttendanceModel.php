<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\Attendance;

/**
 * Description of AttendanceModel
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class AttendanceModel extends AbstractModel{
    const CLASSNAME = '\sms\domain\Attendance';
    const INSRTSTMT = "insert into teaching.attreg (vattreg, dstartdate, denddate) values(?,?,?)";
    const SELSTMT = 'select * from teaching.attreg where vattreg = ?';
    
    
    public function persist(Attendance $att) {
        $result = false;
        try {
            //$insertStmt = "insert into educationinfo (vmedical_info,vtrainer_name, vphone_num, vaddress) values(?,?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $a = $att->getVattreg();
            $s = $att->getDstartdate();
            $e = $att->getDenddate();
            

            $stmt->bindParam(1, $a);
            $stmt->bindParam(2, $s);
            $stmt->bindParam(3, $e);
            

            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $result;
    }
    
    public function getAll(int $page=0, int $pageLength=0): array {
        $stmt = $this->db->prepare("select * from teaching.attendance");
        $result = $stmt->execute();
        if (!$result) {
            
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m;
    }
    
    /**
     * Creates a new register table bearing the 
     * 1. class
     * 2. classroom
     * 3. month / year 
     * @param string $tablename - the name of the table
     * @param array $colnames - column names of the table 
     * @return bool true if the table creation was successful
     */
    public function createRegister(string $tablename, array $colnames): bool {
        $result = false;
        $str = "create table $tablename (vstudent varchar(100) primary key"
                . ",vattendance varchar(100) not null references teaching.attendance(vattreg),"; // part sql string
        foreach($colnames as $value){
            $str .= (new \DateTime("$value"))->format('M_d_Y') ." boolean not null default false,";  // builds the dynamic part of the sql string
        }
        $str = substr($str,0,strlen($str) - 1); // removes the last comma
        $str.=")"; // final part of the sql string
        //echo $str;// for testing purposes
        $stmt = $this->db->prepare($str); // create the sql statement
        if(!$stmt->execute()){ //executes the statement and test if successful  
            print_r($stmt->errorInfo()); //  if not successful it prints out the error
            return $result; // returns result as false
        }
        else {
            $result = true;
            return $result; // returns result as true
        }
    }
}
