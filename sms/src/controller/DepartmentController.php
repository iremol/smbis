<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Department;
use sms\model\DepartmentModel;
use sms\util\UniqueId;

/**
 * Description of DepartmentController
 *
 * @author root
 */
class DepartmentController extends AbstractController{
    public function persist() {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin.twig", []);
        }
        $dept = new Department();
        $params=$this->request->getParams();
        $vdesc=$params->getString("vdescription");
        $util = new UniqueId();
        $model = new DepartmentModel($this->db['db_mgt']);
        $dept->setVdepartment($util->generateId());
        $dept->setVdescription($vdesc);
        if($model->persist($dept)){
            return $this->render("management/createdept.twig", ["msg"=>"Successfully created"]);
        }
        else{
            return $this->render("management/createdept.twig", ["msg"=>"Not Successfully created"]);
        }
    }
    
    public function getAll(): array{
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin.twig", []);
        }
        $model = new DepartmentModel($this->db['db_human_res']);
        return $model->getAll();
    }
}
