<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

/**
 * Description of ManagementController
 *
 * @author imole akpobome <imole.akpobome@gmail.com>
 * @version 1.0
 */
class ManagementController extends AbstractController {
    /**
     * 
     * @return string
     * 
     */
    public function showHome(): string{
        session_start();
        if (isset($_SESSION['uname'])) {
            return $this->render('management/home.twig', ['uname' => $_SESSION['uname']]);
        } else {
            return $this->render("management/mgtlogin.twig", []);
        }
        
    }
    
    public function showCreateDept() {     
        session_start();
        if (isset($_SESSION['uname'])) {
            return $this->render('management/createdept.twig',  ['uname' => $_SESSION['uname']]);
        } else {
            return $this->render("management/mgtlogin.twig", []);
        }
        
    }
    /**
     * @name showCreatePos
     * @return string
     */
    public function showCreatePos() {   
        session_start();
        if (isset($_SESSION['uname'])) {
            return $this->render('management/createpos.twig', ['uname' => $_SESSION['uname']]);
        } else {
            return $this->render("management/mgtlogin.twig", []);
        }
        
    }
    
    /**
     * @access public
     * @return string
     */
    public function viewTeachers():string {
         session_start();
        if (!isset($_SESSION['uname'])) {
           return $this->render("management/mgtlogin.twig", []);
        }
        $teachers = (new PositionController($this->di, $this->request))->getTeachers(); // assigns all teachers record to $teachers 
        return $this->render("management/viewteachers.twig",['t'=>$teachers]); //returns the page to display
    }
}
