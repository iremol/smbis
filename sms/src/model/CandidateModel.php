<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;
use PDOException;
use sms\domain\Candidate;
use sms\exception\NotFoundException;

/**
 * Description of CandidateModel
 *
 * @author root
 */
class CandidateModel extends AbstractModel{
    const CLASSNAME = "\sms\domain\Candidate";
    const INSRTSTMT = "insert into Candidate values(?,?,?)";
    
    public function update(string $app) {
        $query = "update candidate set vstudent_status = 'YES' where vapplication=:app" ;
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute(['app' => $app]);
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
    }
    
    public function get(string $username): Candidate {
        try {
            $query = "select * from Candidate where vusername=:username";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute(['username' => $username]);
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            $candidate = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (empty($candidate)) {
                throw new NotFoundException();
            }
            return $candidate[0];
        } catch (\Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
        }
    }

    public function getAll(int $page=0, int $pageLength=0): array {
        try {
            $query = "select vcandidate,vapplication,vadmission_status from Candidate";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            $candidate = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (empty($candidate)) {
                throw new NotFoundException();
            }
            return $candidate;
        } catch (\Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
        }
    }

    public function getAllGranted(): array {
        try {
            $query = "select vcandidate,vapplication,vadmission_status from Candidate where vadmission_status='GRANTED' and vstudent_status='NO'";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            $candidate = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (empty($candidate)) {
                throw new NotFoundException("There is no existing candidate granted admission.");
            }
            return $candidate;
        } catch (\Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
            print_r($ex);
        }
    }
    
    public function getAllDenied(): array {
        try {
            $query = "select vcandidate,vapplication,vadmission_status from Candidate where vadmission_status='DENIED'";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            $candidate = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (empty($candidate)) {
                throw new NotFoundException();
            }
            return $candidate;
        } catch (\Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
        }
    }
    
    public function getAllNoAdmission(): array {
        try {
            $query = "select vcandidate,vapplication,vadmission_status from Candidate where vadmission_status='NO ADMISSION'";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            $candidate = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (empty($candidate)) {
                throw new NotFoundException();
            }
            return $candidate;
        } catch (\Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
        }
    }
    public function getByCandidate(string $vcandidate): Candidate {
        
    }
    
    public function getByUsername(string $username): Candidate {
        
    }

    public function updateAdmissionGranted(string $vcandidate): bool {
        try {
            $query = "update candidate set vadmission_status ='GRANTED' where vcandidate=?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1,$vcandidate);
            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            //$candidate = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (!$result) {
                throw new NotFoundException();
            }
            return $result;
        } catch (\Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
        }
    }
    
    public function updateAdmissionDenied(string $vcandidate): bool {
        try {
            $query = "update candidate set vadmission_status ='DENIED' where vcandidate=?";
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(1,$vcandidate);
            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                exit();
            }
            //$candidate = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
            if (!$result) {
                throw new NotFoundException();
            }
            return $result;
        } catch (\Exception $ex) {
            echo "Class Not Found " . $ex->getMessage();
        }
    }

    public function search(string $vsurname) {
        
    }

    public function persist(Candidate $candidate): bool {
        $result = false;
        try {
            //$insertStmt = "insert into Candidate values(?,?,?)";

            $stmt = $this->db->prepare(self::INSRTSTMT);
            $can = $candidate->getVcandidate();
            $app = $candidate->getVapplication();
            $us = $candidate->getVusername();


            $stmt->bindParam(1, $can);
            $stmt->bindParam(2, $app);
            $stmt->bindParam(3, $us);

            $result = $stmt->execute();
        } catch (PDOException $e) {
            echo "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        return $result;
    }

}
