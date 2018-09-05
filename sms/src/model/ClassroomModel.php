<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\Classroom;

/**
 * Description of ClassroomModel
 *
 * @author root
 */
class ClassroomModel extends AbstractModel {

    //put your code here
    const CLASSNAME = '\sms\domain\Classroom';
    const INSRTSTMT = "insert into teaching.classroom (vclassroom, vshortdescription) values(?,?)";
    const SELSTMT = 'select * from teaching.classroom where vclassroom = ?';

    public function get(string $vclassroom): Classroom {
        $stmt = $this->db->prepare(self::SELSTMT);
        $stmt->bindParam(1, $vclassroom);
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m[0];
    }

    public function getAll(int $page=0, int $pageLength=0): array {
        $stmt = $this->db->prepare("select vclassroom from teaching.classroom");
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m;
    }

    public function update(string $vclassroom): bool {
        
    }

    public function search(string $vclassroomname) {
        
    }

    public function persist(Classroom $vclassroom): bool {
        $result = false;
        try {
            //$insertStmt = "insert into educationinfo (vmedical_info,vtrainer_name, vphone_num, vaddress) values(?,?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $cr = $vclassroom->getVclassroom();
            $sd = $vclassroom->getVshortdescription();

            $stmt->bindParam(1, $cr);
            $stmt->bindParam(2, $sd);
            
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
