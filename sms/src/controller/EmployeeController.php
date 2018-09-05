<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Employee;
use sms\model\EmployeeModel;
use sms\util\Tools;
use sms\util\UniqueId;

/**
 * Description of EmployeeController
 *
 * @author Imole Akpobome <imole.akpobome@gmail.com>
 * @copyright (c) 2018, Imole Akpobome
 */
class EmployeeController extends AbstractController implements EmployeeMediator{

    public function persist():string {
        session_start();
        if(!isset($_SESSION["uname"])){
            header("Location: /hr/login");
        }
        $params = $this->request->getParams();
        $employee = new Employee();
        $employee->setVemployee(strtoupper((new UniqueId())->generateEmpId()));
        $employee->setVfirstname(strtoupper($params->getString("vfirstname")));
        $employee->setVothername(strtoupper($params->getString("vothername")));
        $employee->setVlastname(strtoupper($params->getString("vlastname")));
        $employee->setVaddress_one($params->getString("vaddress_one"));
        $employee->setVaddress_two($params->getString("vaddress_two"));
        $employee->setVcountry($params->getString("vcountry"));
        $employee->setVstateoforigin($params->getString("vstateoforigin"));
        $employee->setVdepartment($params->getString("vdepartment"));
        $employee->setDdateofemp($params->getString("ddateofemp"));
        $employee->setVposition($params->getString("vposition"));
        $employee->setVpicture(Tools::uploadFile("vpicture"));
        
       
        $model = new EmployeeModel($this->db['db_human_res']);
        if ($model->persist($employee)) {
            return $this->render("hr/home.twig", ['uname' => $_SESSION['uname'], 'msg' => 'New Employee record stored successfully.']);
        } else {
            return $this->render("hr/home.twig", ['uname' => $_SESSION['uname'], 'msg' => 'New Employee not created.']);
        }
    }
    
    public function searchEmployee() {
        session_start();
        if(!isset($_SESSION["uname"])){
            header("Location: /hr/login");
        }
        $params = $this->request->getParams();
        $model = new EmployeeModel($this->db['db_human_res']);
        if ($params->getString('searchById') != null)
            $row = $model->searchEmployee(strtoupper ($params->getString('searchById')));
        elseif (!empty($params->getString('searchByFName')) || !empty($params->getString('searchBySName'))) {
            $row = $model->searchEmployee("", strtoupper($params->getString('searchByFName')), strtoupper($params->getString('searchBySName')));
        }
        if (empty($row[0][0])) {
            return $this->render("hr/search.twig", ["uname" => $_SESSION['uname'], 'msg' => "No record found."]);
        }
        
        
        if (count($row) > 1){
            $properties = ['uname' => $_SESSION['uname'], 'row' => $row,'msg'=>"You have more than 1 student with same name (To be worked On)"];
            return $this->render('admission/search.twig', $properties);
        }
        $properties = ['uname' => $_SESSION['uname'], 'row' => $row];
        return $this->render('hr/search_result.twig', $properties);
    }
    
        public function updateEmployee(string $id) {
        session_start();
        if(!isset($_SESSION["uname"])){
            header("Location: /hr/login");
        }
        $params = $this->request->getParams();
        $model = new EmployeeModel($this->db['db_human_res']);
        $model->update($id, $params->getString("vaddress_one"), $params->getString("vaddress_two"));
        return $this->update($id,"Employee Information updated.");
    }

    public function getEmployee(string $empno) {
        session_start();
        if(!isset($_SESSION["uname"])){
            header("Location: /hr/login");
        }
        return (new EmployeeModel($this->db['db_human_res']))->searchEmployee($empno);
    }
    
    public function update(string $id,$msg="") {
        session_start();
        if(!isset($_SESSION["uname"])){
            header("Location: /hr/login");
        }
        
        $model = new EmployeeModel($this->db['db_human_res']);
        $row = $model->searchEmployee($id);
        $properties = ['uname' => $_SESSION['uname'], 'row' => $row,'msg'=>$msg];
        return $this->render("hr/update_emp.twig", $properties);
    }
}
