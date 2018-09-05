<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\Class_;

/**
 * Description of ClassModel
 *
 * @author root
 */
class ClassModel extends AbstractModel{
    //put your code here
     const CLASSNAME = '\sms\domain\Class_';
    const INSRTSTMT = "insert into teaching.class (vclass, vclassteacher, vclassname,vclassroom,bsid, vattreg, ssdaypattern) values(?,?,?,?,?,?,?)";
    const SELSTMT = 'select * from teaching.class where vclass = ?';

    public function get(string $vclass): Class_ {
        $stmt = $this->db->prepare(self::SELSTMT);
        $stmt->bindParam(1, $vclass);
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m[0];
    }

    public function getClassesId(int $page=0, int $pageLength=0): array {
        $stmt = $this->db->prepare("select vclass from teaching.class");
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m;
    }
    
    public function getAll(int $page=0, int $pageLength=0): array {
        $stmt = $this->db->prepare("select * from teaching.class");
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m;
    }


    public function update(string $vclass): bool {
        
    }

    public function search(string $vclassname) {
        
    }

    public function persist(Class_ $vclass): bool {
        $result = false;
        try {
            //$insertStmt = "insert into educationinfo (vmedical_info,vtrainer_name, vphone_num, vaddress) values(?,?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $c = $vclass->getVclass();
            $cn = $vclass->getVclassname();
            $cr = $vclass->getVclassroom();
            $ct = $vclass->getVclassteacher();
            $b = $vclass->getBsid();
            $att = $vclass->getVattreg();
            $s = $vclass->getSsdaypattern();

            $stmt->bindParam(1, $c);
            $stmt->bindParam(2, $ct);
            $stmt->bindParam(3, $cn);
            $stmt->bindParam(4, $cr);
            $stmt->bindParam(5, $b);
            $stmt->bindParam(6, $att);
            $stmt->bindParam(7, $s);
            

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
