<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Classroom;
use sms\model\ClassroomModel;
use sms\util\UniqueId;

/**
 * Description of ClassroomController
 *
 * @author root
 */
class ClassroomController extends AbstractController {

    //put your code here
    public function newroom() {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin.twig", []);
        }
        return $this->render('management/createclassroom.twig', ["uname"=>$_SESSION["uname"]]);
    }

    public function persist() {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin.twig", []);
        }
        $result = false;
        $classrm = new Classroom();
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $properties = ['uname' => $cookies->get('uname')];
        $unique = new UniqueId();
        $classrm->setVclassroom(strtoupper($params->getString('vclassroom')));
        $classrm->setVshortdescription(strtoupper($params->getString('vshortdescription')));

        //$appCont = new ApplicationController($this->di, $this->request);


        $model = new ClassroomModel($this->db["db_mgt"]);
        $result = $model->persist($classrm);
        //return $result;
        if ($result) {
            return $this->render('management/createclassroom.twig', ['msg'=>'Classroom successfully created']);
        } else {
            return $this->render('management/createclassroom.twig', ['msg'=>'Classroom creation failed']);
        }
    }

    public function get(string $vmedical_info): Classroom {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin.twig", []);
        }
        $model = new ClassroomModel($this->db["db_mgt"]);
        return $model->get($vmedical_info);
    }
    
    public function getAll() :array {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin.twig", []);
        }
        $model = new ClassroomModel($this->db["db_mgt"]);
        return $model->getAll();
    }

}
