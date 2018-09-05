<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

/**
 * Description of HumanResource
 *
 * @author root
 */
class HumanResourceController extends AbstractController {

    //put your code here
    public function showHome() {
        session_start();
        if (isset($_SESSION['uname'])) {
            return $this->render("hr/home.twig", ['uname' => $_SESSION['uname']]);
        } else {
            return $this->render("hr/hrlogin.twig", []);
        }
    }

    public function showLogin() {
        return $this->render("hr/hrlogin.twig", []);
    }

    public function showLogout() {
        return $this->render("hr/hrlogout.twig", []);
    }

    public function showLoginCreate() {
        return $this->render("hr/createlogin.twig", []);
    }

    public function showEmpCreate() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render("hr/hrlogin.twig", []);
        } else {
            $cObj = new CountriesController($this->di, $this->request);
            $carr = $cObj->getAll();

            $dObJ = new DepartmentController($this->di, $this->request);
            $darr = $dObJ->getAll();

            $pObJ = new PositionController($this->di, $this->request);
            $parr = $pObJ->getAll();

            $arr = ['c' => $carr, "uname" => $_SESSION['uname'], 'd' => $darr, 'p' => $parr];
            return $this->render("hr/createemp.twig", $arr);
        }
    }

    public function showEmpSearch() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render("hr/hrlogin.twig", []);
        } else {
            return $this->render("hr/search.twig", ["uname" => $_SESSION['uname']]);
        }
    }

    public function showEmpUpdate(string $empnno) {
        session_start();
         session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render("hr/hrlogin.twig", []);
        } else {
            $empCon = (new EmployeeController($this->di, $this->request));
            $arr = $empCon->getEmployee($empnno);
            return $this->render("hr/update_emp.twig", ["uname" => $_SESSION['uname'],'row'=>$arr]);
        }
    }

}
