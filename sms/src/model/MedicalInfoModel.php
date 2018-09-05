<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\MedicalInfo;

/**
 * Description of MedicalInfoModel
 *
 * @author root
 */
class MedicalInfoModel extends AbstractModel {

    //put your code here
    const CLASSNAME = '\sms\domain\MedicalInfo';
    const INSRTSTMT = "insert into medicalinfo (vmedical_info, vconditions) values(?,?)";
    const SELSTMT = 'select * from medicalinfo where vmedical_info = ?';

    public function get(string $vmedical_info): MedicalInfo {
        $stmt = $this->db->prepare(self::SELSTMT);
        $stmt->bindParam(1, $vmedical_info);
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m[0];
    }

    public function getAll(int $page, int $pageLength): array {
        
    }

    public function getByCandidate(string $vcandidate): MedicalInfo {
        
    }

    public function update(string $vmedical_info): bool {
        
    }

    public function search(string $vsurname, string $vfirstname, string $vothernames) {
        
    }

    public function persist(MedicalInfo $medicalInfo): bool {
        $result = false;
        try {
            //$insertStmt = "insert into educationinfo (vmedical_info,vtrainer_name, vphone_num, vaddress) values(?,?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $mi = $medicalInfo->getVMedical_Info();
            $c = $medicalInfo->getVConditions();

            $stmt->bindParam(1, $mi);
            $stmt->bindParam(2, $c);

            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo);
            }
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $result;
    }

}
