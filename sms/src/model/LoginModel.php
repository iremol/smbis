<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDOException;
use sms\domain\Login;
use sms\exception\DbException;

/**
 * Description of LoginModel
 *
 * @author root
 */
class LoginModel extends AbstractModel {

    const CLASSNAME = "\sms\domain\Login";

    /**
     * @name Login(string, string)
     * @param string $usernmae The username credential of applicant.
     * @param string $password THe password credential of applicant.
     */
    public function login(string $username, string $password) {
        $query = "select vusername,vpassword from login where vusername=:name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $username);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        $row = $stmt->fetch();
        return password_verify($password, $row['vpassword']);
    }

    /**
     * @name aoLogin(string, string)
     * @param string $usernmae The username credential of Admission officer.
     * @param string $password THe password credential of Admission officer.
     */
    public function aoLogin(string $username, string $password): bool {
        $query = "select vusername,vpassword from admission.login where vusername=:name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $username);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        $row = $stmt->fetch();
        return password_verify($password, $row['vpassword']);
    }

    /**
     * @name hrLogin(string, string)
     * @param string $usernmae The username credential of human resource.
     * @param string $password THe password credential of human resource.
     */
    public function hrLogin(string $username, string $password): bool {
        $query = "select vusername,vpassword from hr.login where vusername=:name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $username);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        $row = $stmt->fetch();
        return password_verify($password, $row['vpassword']);
    }
    /**
     * @name mgtLogin(string, string).
     * @param string $username The username credential of Administrator.
     * @param string $password THe password credential of Administrator.
     */
    public function mgtLogin(string $username, string $password): bool {
        $query = "select vusername,vpassword from mgt.login where vusername=:name";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(":name", $username);
        $result = $stmt->execute();
        if (!$result) {
            return false;
        }
        $row = $stmt->fetch();
        return password_verify($password, $row['vpassword']);
    }

    public function get(string $vusername): Login {
        
    }

    public function getAll(int $page, int $pageLength): array {
        
    }

    public function getByCandidate(string $vcandidate): Login {
        
    }

    public function update(string $vusername): bool {
        
    }

    public function search(string $vsurname) {
        
    }

    public function persist(Login $login): bool {
        $result = false;
        $insertStmt = "insert into Login values(?,?)";

        $stmt = $this->db->prepare($insertStmt);
        $us = $login->getVusername();
        $pw = $login->getVpassword();


        $stmt->bindParam(1, $us);
        $stmt->bindParam(2, $pw);

        $result = $stmt->execute();

        if (!$result) {
            $err = $stmt->errorInfo();
            throw new DbException($err[0] . ": " . $err[1] . ": " . $err[2]);
        }
        return $result;
    }

    /**
     * Persists login information for Admission Office (AO)
     * @param Login $login
     * @return bool
     */
    public function persistAO(Login $login): bool {
        $result = false;
        try {
            $insertStmt = "insert into admission.login values(?,?)";

            $stmt = $this->db->prepare($insertStmt);
            $us = $login->getVusername();
            $pw = $login->getVpassword();


            $stmt->bindParam(1, $us);
            $stmt->bindParam(2, $pw);

            $result = $stmt->execute();
            if (!$result) {
                print_r($stmt->errorInfo());
                die();
            }
        } catch (PDOException $e) {
            echo "Error!: " . $stmt->errorInfo() . "<br/>";
            die();
        }
        return $result;
    }

    public function persistHR(Login $login): bool {
        $insertStmt = "insert into hr.login values(?,?)";
        $stmt = $this->db->prepare($insertStmt);
        $stmt->bindParam(1, $login->getVusername());
        $stmt->bindParam(2, $login->getVpassword());
        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());
            return false;
        } else {
            return true;
        }
    }
    
    public function persistMgt(Login $login): bool {
        $insertStmt = "insert into mgt.login values(?,?)";
        $stmt = $this->db->prepare($insertStmt);
        $stmt->bindParam(1, $login->getVusername());
        $stmt->bindParam(2, $login->getVpassword());
        if (!$stmt->execute()) {
            print_r($stmt->errorInfo());
            return false;
        } else {
            return true;
        }
    }

}
