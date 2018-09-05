<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\EducationInfo;

/**
 * Description of EducationInfoModel
 *
 * @author root
 */
class EducationInfoModel extends AbstractModel {
    //put your code here
	const CLASSNAME = '\sms\domain\EducationInfo';
	const INSRTSTMT = "insert into educationinfo (veducation_info,vtrainer_name, vphone_num, vaddress) values(?,?,?,?)";
        const SELSTMT = "select * from educationinfo where veducation_info = ? ";
    
    public function get(string $veducation_info): EducationInfo {
         $stmt = $this->db->prepare(self::SELSTMT);
        $stmt->bindParam(1,$veducation_info);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
        }
        $e = $stmt->fetchAll(PDO::FETCH_CLASS,self::CLASSNAME);
        return $e[0];
    }

    public function getAll(int $page, int $pageLength): array {
        
    }

    public function getByCandidate(string $vcandidate): EducationInfo {
        
    }

    public function update(string $veducation_info): bool {
        
    }

    public function search(string $vsurname, string $vfirstname, string $vothernames) {
        
    }

    public function persist(EducationInfo $educationInfo): bool {
        $result=false;
        try {
            //$insertStmt = "insert into educationinfo (veducation_info,vtrainer_name, vphone_num, vaddress) values(?,?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $ei = $educationInfo->getVeducation_info();
            $ti = $educationInfo->getVtrainer_name();
            $pn = $educationInfo->getVphone_num();
            $ad = $educationInfo->getVaddress();

            $stmt->bindParam(1, $ei);
            $stmt->bindParam(2, $ti);
            $stmt->bindParam(3, $pn);
            $stmt->bindParam(4, $ad);

            $result = $stmt->execute();
            if(!$result){
                  print_r($stmt->errorInfo());
            }
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $result;
    }

}
