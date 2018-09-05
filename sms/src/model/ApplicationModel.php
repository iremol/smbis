<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use Exception;
use PDO;
use PDOException;
use sms\domain\Application;
use sms\exception\DbException;
use sms\exception\NotFoundException;

/**
 * Description of ApplicationModel
 *
 * @author root
 */
class ApplicationModel extends AbstractModel {

    const CLASSNAME = "\sms\domain\Application";
    const INSRTSTMT = "insert into Application (vapplication,vbio_info,vguardian_info,veducation_info,vmedical_info,vsibling,ddateofapplication) values (?,?,?,?,?,?,?)";

    public function get(string $application): Application {
        //$application = null;
        try {
            $query = "select * from Application where vapplication=:application";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute(['application' => $application]);
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            $application = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (empty($application)) {
                throw new NotFoundException();
            }
            return $application[0];
        } catch (Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
        }
    }

    public function getAll(int $page, int $pageLength): array {
        
    }

    public function getByApplication(string $vapplication): Application {
        
    }

    public function update(Application $vapplication): bool {
        $result = false;
        try {
            $update = "update Application set vbio_info=:vbioinfo, vguardian_info=:vguardianinfo, veducation_info=:veduinfo,"
                    . "vmedical_info=:vmedinfo, vsibling=:vsibling where vapplication=:appid";
            $stmt = $this->db->prepare($update);
            $b = $vapplication->getVbio_info();
            $g = $vapplication->getVguardian_info();
            $e = $vapplication->getVeducation_info();
            $m = $vapplication->getVmedical_info();
            $s = $vapplication->getVsibling();
            $a = $vapplication->getVapplication();

            $stmt->bindParam(':vbioinfo', $b);
            $stmt->bindParam(':vguardianinfo', $g);
            $stmt->bindParam(':veduinfo', $e);
            $stmt->bindParam(':vmedinfo', $m);
            $stmt->bindParam(':vsibling', $s);
            $stmt->bindParam(':appid', $a);

            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
        } catch (Exception $ex) {
            
        }
        return $result;
    }

    public function search(string $vsurname) {
        
    }

    public function persist_old(Application $application): bool {
        $result = false;
        try {
            $insertStmt = "insert into Application (vapplication, ddateofapplication) values(?,?)";

            $stmt = $this->db->prepare($insertStmt);
            $app = $application->getVapplication();
            $date = $application->getDdateofapplication();


            $stmt->bindParam(1, $app);
            $stmt->bindParam(2, $date);

            $result = $stmt->execute();
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $result;
    }

    public function persist(Application $application): bool {
        $result = false;
        $stmt = $this->db->prepare(self::INSRTSTMT);
        $app = $application->getVapplication();
        $date = $application->getDdateofapplication();
        $bi = $application->getVbio_info();
        $ei = $application->getVeducation_info();
        $gi = $application->getVguardian_info();
        $mi = $application->getVmedical_info();
        $sib = null;
        $stmt->bindParam(1, $app);
        $stmt->bindParam(6, $sib);
        $stmt->bindParam(7, $date);
        $stmt->bindParam(2, $bi);
        $stmt->bindParam(4, $ei);
        $stmt->bindParam(3, $gi);
        $stmt->bindParam(5, $mi);

        $result = $stmt->execute();

        if (!$result) {
            $err=$stmt->errorInfo();
            throw new DbException($err[0].": ".$err[1].": ".$err[2]);
        }
        return $result;
    }

}
