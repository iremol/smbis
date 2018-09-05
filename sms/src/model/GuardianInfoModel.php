<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\GuardianInfo;

/**
 * Description of GuardianInfoModel
 *
 * @author root
 */
class GuardianInfoModel extends AbstractModel {

    const CLASSNAME = "\sms\domain\GuardianInfo";
    const INSRTSTMT = "insert into guardianinfo (vguardian_info,vtitle, vsurname, vfirstname, vothernames,vaddress"
            . ",vpostcode,vemail,vdaytime_no,vrelationship) values(?,?,?,?,?,?,?,?,?,?)";
    const SELSTMT = "select * from guardianinfo where vguardian_info = ? ";

    public function get(string $vguardian_info): GuardianInfo {
        $stmt = $this->db->prepare(self::SELSTMT);
        $stmt->bindParam(1,$vguardian_info);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
        }
        $g = $stmt->fetchAll(PDO::FETCH_CLASS,self::CLASSNAME);
        return $g[0];
    }

    public function getAll(int $page, int $pageLength): array {
        
    }

    public function getByCandidate(string $vcandidate): GuardianInfo {
        
    }

    public function update(string $vguardian_info): bool {
        
    }

    public function search(string $vsurname, string $vfirstname, string $vothernames) {
        
    }

    public function persist(GuardianInfo $guardianInfo): bool {
        $result = false;
        try {
        //$insertStmt = "insert into guardianinfo (vguardian_info,vtitle, vsurname, vfirstname, vothernames,vaddress"
        //. ",vpostcode,vemail,vdaytime_no,vrelationship) values(?,?,?,?,?,?,?,?,?,?)";

        $stmt = $this->db->prepare(self::INSRTSTMT);
        $gi = $guardianInfo->getVguardian_info();
        $ti = $guardianInfo->getVtitle();
        $sn = $guardianInfo->getVsurname();
        $fn = $guardianInfo->getVfirstname();
        $on = $guardianInfo->getVothername();
        $addr = $guardianInfo->getVaddress();
        $pc = $guardianInfo->getVpostcode();
        $em = $guardianInfo->getVemail();
        $dtno = $guardianInfo->getVdaytime_no();
        $rl = $guardianInfo->getVrelationship();

        $stmt->bindParam(1, $gi);
        $stmt->bindParam(2, $ti);
        $stmt->bindParam(3, $sn);
        $stmt->bindParam(4, $fn);
        $stmt->bindParam(5, $on);
        $stmt->bindParam(6, $addr);
        $stmt->bindParam(7, $pc);
        $stmt->bindParam(8, $em);
        $stmt->bindParam(9, $dtno);
        $stmt->bindParam(10, $rl);

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
