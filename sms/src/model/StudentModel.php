<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\controller\CandidateController;
use sms\domain\Student;

/**
 * Description of StudentModel
 *
 * @author root
 */
class StudentModel extends AbstractModel {

    //put your code here
    const CLASSNAME = '\sms\domain\Student';
    const INSRTSTMT = "insert into teaching.student (vstudent, vapplication, vclass) values(?,?,?)";
    const SELSTMT = 'select * from teaching.student where vstudent = ?';

    public function get(string $vstudent): Student {
        $stmt = $this->db->prepare(self::SELSTMT);
        $stmt->bindParam(1, $vstudent);
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m[0];
    }

    public function getAll(int $page, int $pageLength): array {
        
    }

    public function update(string $vstudent, string $c_addr,string $c_class, string $g_addr, string $email, string $dtno) {
        $this->db->beginTransaction();
        $stmt=$this->db->prepare("select * from updateguardian('$vstudent','$g_addr','$email','$dtno')");
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            $this->db->rollback();
        }
        $stmt = $this->db->prepare("select * from updatestudent('$vstudent','$c_addr','$c_class')");
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            $this->db->rollback();
        }
        $this->db->commit();
    }

    public function searchStudent(string $vstudent = "", string $vfirstname = "", string $vsurname = "") : array {
        if (!empty($vstudent)) {
            try {
                $stmt = $this->db->prepare("select * from searchStudent(?)");
                $stmt->bindParam(1, $vstudent);
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
                $stmt = $this->db->prepare("select * from searchStudent(?,?)");
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

    public function persist(Student $vstudent): bool {
        $result = false;
        try {
            //$insertStmt = "insert into educationinfo (vmedical_info,vtrainer_name, vphone_num, vaddress) values(?,?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $s = $vstudent->getVstudent();
            $a = $vstudent->getVapplication();
            $c = $vstudent->getVclass();

            $stmt->bindParam(1, $s);
            $stmt->bindParam(2, $a);
            $stmt->bindParam(3, $c);

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

}
