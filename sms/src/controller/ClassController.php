<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Class_;
use sms\model\ClassModel;
use sms\util\UniqueId;
use sms\util\Tools;

/**
 * Description of ClassController
 *
 * @author root
 */
class ClassController extends AbstractController {

    private $msg;

    /**
     * @return string
     */
    public function newClass($msg = ""): string {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin.twig", []);
        }
        $classroom_rec = (new ClassroomController($this->di, $this->request))->getAll(); // array containing all classroom records
        $duration_rec = (new ClassDurationController($this->di, $this->request))->getAll(); // array containing all duration records  
        $teacher_rec = (new PositionController($this->di, $this->request))->getTeachers(); // array containing all teachers records
        $att_id = (new UniqueId())->generateAttId();
        $dayp = (new DayPatternController($this->di, $this->request))->getAll();
        return $this->render("management/createclass.twig", ["cr" => $classroom_rec, "cd" => $duration_rec, "t" => $teacher_rec, "att_id" => $att_id, "dp" => $dayp, "msg" => $msg, "uname"=>$_SESSION['uname']]); // page to display containg all records
    }

    public function viewClasses($msg = ""): string {
        session_start();
        $this->sessionEnforcer($this,$_SESSION["uname"] , "management/mgtlogin.twig");
        return $this->render("management/viewclasses.twig",["classes" => ($this->getAll()), "uname" => $_SESSION['uname']]);
    }
    /**
     * @return string 
     */
    public function persist(): string {
        session_start(); //resumes the session
        $this->sessionEnforcer($this, $_SESSION['uname'], 'management/mgtlogin.twig'); //checks validity of session
        $attendanceCont = new AttendanceController($this->di, $this->request); 
        $result = $attendanceCont->persist();

        if ($result) {
            $class = new Class_();
            $params = $this->request->getParams();
            $cookies = $this->request->getCookies();

            $properties = ['uname' => $cookies->get('uname')];

            $class->setVclass(strtoupper($params->getString("vclass")));
            $class->setVclassname(strtoupper($params->getString('vclassname')));
            $class->setVclassteacher(strtoupper($params->getString('vclassteacher')));
            $class->setVclassroom(($params->getString('vclassroom')));
            $class->setBsid(intval($params->getString("bsid")));
            $class->setVattreg(($params->getString("vattendanceid")));
            $class->setSsdaypattern(intval($params->getString("ssdaypattern")));
            $model = new ClassModel($this->db['db_mgt']);
            $result = $model->persist($class);

            if ($result) {
//                return $this->render('management/createclass.twig', ['msg' => 'Class successfully created']);
                //return header("Location: http://localhost:8000/mgt/class/new");
                return $this->newClass('Class successfully created');
            } else {
                //return $this->render('management/createclass.twig', ['msg' => 'Class creation failed']);
                return $this->newClass('Class creation failed');
            }
        }
//        if ($result) {
//            $appCont = new ApplicationController($this->di, $this->request);
//            $app = $appCont->get($cookies->getString('app'));
//            $app->setVeducation_info($class->getVeducation_info());
//            if ($appCont->update($app)) {
//                return $this->render('application/sibling.twig', $properties);
//            } else {
//                return $this->render('error.twig', ['errorMessage' => 'Could not update Application Table']);
//            }
//        } else {
//            return $this->render('error.twig', []);
//        }
        return "";
    }

    /**
     * 
     * @param string $vclass
     * @return Class_
     */
    public function get(string $vclass): Class_ {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin", []);
        }
        return $model = (new ClassModel($this->db["db_mgt"]))->get($vclass);
    }

    /**
     * 
     * @return array
     */
    public function getAll(): array {
        session_start();
        if(!isset($_SESSION['uname'])){
            return $this->render("management/mgtlogin", []);
        }
        return $model = (new ClassModel($this->db["db_mgt"]))->getAll();
    }

}
