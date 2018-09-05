<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Student;
use sms\model\StudentModel;
use sms\util\UniqueId;

/**
 * Description of StudentController
 *
 * @author root
 */
class StudentController extends AbstractController {

    //put your code here
    public function persist() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('admission/logout', []);
        }
        $result = false;
        $student = new Student();
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $properties = ['uname' => $cookies->get('uname')];
        $unique = new UniqueId();
        $student->setVstudent(strtoupper($unique->generateStudentId()));
        $student->setVapplication($params->getString('vapplication'));
        $student->setVclass(strtoupper($params->getString('vclass')));

        //$appCont = new ApplicationController($this->di, $this->request);


        $model = new StudentModel($this->db["db_admission"]);
        $result = $model->persist($student);

        if ($result) {
            $candidateCont = new CandidateController($this->di, $this->request);
            return $candidateCont->update($student->getVapplication());
            //return $this->render('admission/candidates_granted.twig', ['msg' => 'Student successfully created']);
            //header('/admission/granted');
        } else {

            return $this->render('admission/candidates_granted.twig', ['msg' => 'Student creation failed']);
            //header('/admission/granted');
        }
        return $result;
    }

    public function get(string $vmedical_info): Student {
        $model = new StudentModel($this->db["db_admission"]);
        return $model->get($vmedical_info);
    }

    public function showTransfer(string $app) {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('admission/logout', []);
        }
        $classCont = new ClassController($this->di, $this->request);
        $classarr = $classCont->getAll();
        return $this->render("admission/transfer.twig", ["app" => $app, "uname" => $_SESSION['uname'], "c" => $classarr]);
    }

    public function search() {
        session_start();
        return $this->render("admission/search.twig", ["uname" => $_SESSION['uname']]);
    }

    public function update(string $id,$msg="") {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('admission/logout', []);
        }
        $model = new StudentModel($this->db["db_admission"]);
        $row = $model->searchStudent($id);
        $properties = ['uname' => $_SESSION['uname'], 'row' => $row,'msg'=>$msg];
        return $this->render("admission/update_student.twig", $properties);
    }

    public function updateStudent(string $id) {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('admission/logout', []);
        }
        $params = $this->request->getParams();
        $model = new StudentModel($this->db["db_admission"]);
        $model->update($id, $params->getString("c_addr"), $params->getString("vclass"), $params->getString("g_addr"), $params->getString("g_email"), $params->getString("g_dtno"));
        return $this->update($id,"Student Information updated.");
    }

    public function searchStudent() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('admission/logout', []);
        }
        $params = $this->request->getParams();
        $model = new StudentModel($this->db["db_admission"]);
        if ($params->getString('searchById') != null)
            $row = $model->searchStudent($params->getString('searchById'));
        elseif (!empty($params->getString('searchByFName')) || !empty($params->getString('searchBySName'))) {
            $row = $model->searchStudent("", $params->getString('searchByFName'), $params->getString('searchBySName'));
        }
        if (empty($row[0][0])) {
            $this->log->info('['.$_SESSION['uname'].'] '.'Search return no results',array('cause'=>'Record for '.$params->getString('searchById').$params->getString('searchByFName').$params->getString('searchBySName').' does not exists'));
            return $this->render("admission/search.twig", ["uname" => $_SESSION['uname'], 'msg' => "No record found."]);
            
        }
        
        
        if (count($row) > 1){
            $properties = ['uname' => $_SESSION['uname'], 'row' => $row,'msg'=>"You have more than 1 student with same name (To be worked On)"];
            return $this->render('admission/search.twig', $properties);
        }
        $properties = ['uname' => $_SESSION['uname'], 'row' => $row];
        $this->log->info('['.$_SESSION['uname'].'] '.'Search successful');
        return $this->render('admission/search_result.twig', $properties);
    }

}
