<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\BioInfo;

/**
 * Description of BioInfoModel
 *
 * @author root
 */
class BioInfoModel extends AbstractModel {

    const CLASSNAME = "\sms\domain\BioInfo";
    const INSRTSTMT = "insert into bioinfo (vbio_info, vsurname, vfirstname, vothernames, vgender, ddob, vaddress,vpic) values(?,?,?,?,?,?,?,?)";
    const SELSTMT = "select * from bioinfo where vbio_info = ? ";
    public function get(string $vbio_info): BioInfo {
        $stmt = $this->db->prepare(self::SELSTMT);
        $stmt->bindParam(1,$vbio_info);
        $result = $stmt->execute();
        if(!$result){
            print_r($stmt->errorInfo());
        }
        $b = $stmt->fetchAll(PDO::FETCH_CLASS,self::CLASSNAME);
        return $b[0];
    }

    public function getAll(int $page, int $pageLength): array {
        
    }

    public function getByCandidate(string $vcandidate): BioInfo {
        
    }

    public function update(string $vbio_info): bool {
        
    }

    public function search(string $vsurname, string $vfirstname, string $vothernames) {
        
    }

    public function persist(BioInfo $bioInfo): bool {
        $result = false;
        try {
            //$insertStmt = "insert into bioinfo (vbio_info, vsurname, vfirstname, vothernames, vgender, ddob, vaddress) values(?,?,?,?,?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $bi = $bioInfo->getVbio_info();
            $sn = $bioInfo->getVsurname();
            $fn = $bioInfo->getVfirstname();
            $on = $bioInfo->getVothernames();
            $g = $bioInfo->getVgender();
            $dob = $bioInfo->getDdob();
            $addr = $bioInfo->getVaddress();
            $pic = $bioInfo->getVpic();

            $stmt->bindParam(1, $bi);
            $stmt->bindParam(2, $sn);
            $stmt->bindParam(3, $fn);
            $stmt->bindParam(4, $on);
            $stmt->bindParam(5, $g);
            $stmt->bindParam(6, $dob);
            $stmt->bindParam(7, $addr);
            $stmt->bindParam(8, $pic);

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
