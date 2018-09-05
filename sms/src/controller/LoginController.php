<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Application;
use sms\domain\Login;
use sms\model\LoginModel;
use sms\domain\Candidate;

/**
 * Description of LoginController
 *
 * @author root
 */
class LoginController extends AbstractController {

    private $properties;

    public function persist(): bool {
        $login = new Login();
        $params = $this->request->getParams();
        $login->setVusername($params->getString('username'));
        $login->setVpassword(password_hash($params->getString('password'), PASSWORD_DEFAULT));

        $model = new LoginModel($this->db["db_applicant"]);
        $result = $model->persist($login);
        if ($result) {
            $appController = new ApplicationController($this->di, $this->request);
            $app = $appController->persist();
            if ($app == null) {
                echo "whala sele";
                exit();
            } else {
                $canController = new CandidateController($this->di, $this->request);
                $result = $canController->persist($login->getVusername(), $app->getVapplication());
            }
        } else {
            echo "Issues with Login Insert";
        }
        return $result;
    }

    /**
     * Method persistAO() persists Admission OFficer logon 
     * Details.
     * @return bool
     */
    public function persistAO(): bool {
        $login = new Login();
        $params = $this->request->getParams();
        $login->setVusername($params->getString('username'));
        $login->setVpassword(password_hash($params->getString('password'), PASSWORD_DEFAULT));

        $model = new LoginModel($this->db['db_applicant']);
        $result = $model->persistAO($login);
        return $result;
    }

    /**
     * Method persistHR() persists Admission OFficer logon 
     * Details.
     * @return bool
     */
    public function persistHR(): string {
        $login = new Login();
        $params = $this->request->getParams();
        $login->setVusername($params->getString('username'));
        $login->setVpassword(password_hash($params->getString('password'), PASSWORD_DEFAULT));

        $model = new LoginModel($this->db['db_human_res']);
        $result = $model->persistHR($login);
        return $result ? $this->render("hr/hrlogin.twig", ["msg" => "Login Successfully created"]) : $this->render("hr/hrlogin.twig", ["msg" => "Login creation failed"]);
    }
    
    /**
     * Method persistMgt() persists Administrator logon 
     * Details.
     * @return bool
     */
    public function persistMgt(): string {
        $login = new Login();
        $params = $this->request->getParams();
        $login->setVusername($params->getString('username'));
        $login->setVpassword(password_hash($params->getString('password'), PASSWORD_DEFAULT));

        $model = new LoginModel($this->db['db_mgt']);
        $result = $model->persistMgt($login);
        return $result ? $this->render("management/createlogin.twig", ["msg" => "Login Successfully created"]) : $this->render("management/createlogin.twig", ["msg" => "Login creation failed"]);
    }

    public function showNew() {
        return $this->render('application/loginprofile.twig', []);
    }

    public function createAOLogin() {
        return $this->render('admission/createlogin.twig', []);
    }
    
    public function createMgtLogin() {
        return $this->render('management/createlogin.twig', []);
    }

    public function showLogin() {
        return $this->render('admission/aologin.twig', []);
    }

    public function login() {
        return $this->render('application/login.twig', []);
    }

    public function showHRLogin() {
        return $this->render("hr/hrlogin.twig", []);
    }
    
    public function showTeachingLogin() {
        return $this->render("teacher/teachinglogin.twig",[]);
    }

    public function showMgtLogin() {
        return $this->render("management/mgtlogin.twig",[]);
    }
    public function successLogin() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render('error.twig', []);
        }
        $this->properties = ['uname' => strtoupper($_COOKIE['uname'])];
        return $this->render('application/profile.twig', $this->properties);
    }

    public function successAoLogin(string $msg = "") {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render('admission/aologin.twig', ["msg"=>$msg]);
            //header("Location: /admission/login");
        }
        $this->properties = ['uname' => strtoupper($_COOKIE['uname'])];
        return $this->render('admission/home.twig', $this->properties);
    }
    
    public function successMgtLogin(string $msg = "") {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render('management/mgtlogin.twig', ["msg"=>$msg]);
            //header("Location: /mgt/login");
            
        }
        $this->properties = ['uname' => strtoupper($_COOKIE['uname'])];
        return $this->render('management/home.twig', $this->properties);
    }

    public function authenticate() {
        $params = $this->request->getParams();
        $model = new LoginModel($this->db["db_applicant"]);
        if ($model->login($params->getString('username'), $params->getString('password'))) {
            session_start();
            $_SESSION['uname'] = $params->getString('username');
            setcookie('uname', $params->getString('username'), time() + 14400, '/');
            $canController = new CandidateController($this->di, $this->request);
            $can = $canController->get();
            setcookie('app', $can->getVapplication(), time() + 14400, '/');
            $appCon = new ApplicationController($this->di, $this->request);
            $app = $appCon->get($can->getVapplication());
            $this->properties = ['uname' => strtoupper($params->getString('username'))];
            $arr = $this->formPageChecker($app);
            $this->properties = array_merge($this->properties, $arr);
            header('Location: /application/home');
            //return $this->render($this->properties['path'], $this->properties);
            //return $this->render('application/profile.twig', $this->properties);
        } else {
            return $this->render('application/login.twig', []);
        }
    }

    public function aoAuthenticate() {
        $params = $this->request->getParams();
        $model = new LoginModel($this->db["db_admission"]);
        if ($model->aoLogin($params->getString('username'), $params->getString('password'))) {
            session_start();
            $_SESSION['uname'] = $params->getString('username');
            setcookie('uname', $params->getString('username'), time() + 14400, '/');
            $this->properties = ['uname' => strtoupper($params->getString('username'))];
            header('Location: /admission/home');
            //return $this->render($this->properties['path'], $this->properties);
            //return $this->render('application/profile.twig', $this->properties);
        } else {
            return $this->successAoLogin("Failed Authentication");
            //header('Location: /admission/login');
            //return $this->render('admission/aologin.twig', []);
        }
    }

    /**
     * @name hrAuthenticate
     * @return string
     */
    public function hrAuthenticate(): string {
        $params = $this->request->getParams();
        $model = new LoginModel($this->db["db_human_res"]);
        if ($model->hrLogin($params->getString('username'), $params->getString('password'))) {
            session_start();
            $_SESSION['uname'] = $params->getString('username');
            setcookie('uname', $params->getString('username'), time() + 14400, '/');
            $this->properties = ['uname' => strtoupper($params->getString('username'))];
            header('Location: /hr/home');
            //return $this->render($this->properties['path'], $this->properties);
            //return $this->render('application/profile.twig', $this->properties);
        } else {
            header('Location: /hr/login');
            //return $this->render('admission/aologin.twig', []);
        }
    }
    
    /**
     * @name mgtAuthenticate
     * @return string
     */
    public function mgtAuthenticate(): string {
        $params = $this->request->getParams();
        $model = new LoginModel($this->db["db_mgt"]);
        if ($model->mgtLogin($params->getString('username'), $params->getString('password'))) {
            session_start();
            $_SESSION['uname'] = $params->getString('username');
            setcookie('uname', $params->getString('username'), time() + 14400, '/');
            $this->properties = ['uname' => strtoupper($params->getString('username'))];
            //return $this->successMgtLogin("Success");
            header('Location: /mgt/home');
            //return $this->render($this->properties['path'], $this->properties);
            //return $this->render('application/profile.twig', $this->properties);
        } else {
            return $this->successMgtLogin("Failed Authentication");
            //header('Location: /mgt/login');
            //return $this->render('admission/aologin.twig', []);
        }
    }

    public function navigate(): string {
        $canController = new CandidateController($this->di, $this->request);
        $can = $canController->get();
        setcookie('app', $can->getVapplication(), time() + 14400, '/');
        $appCon = new ApplicationController($this->di, $this->request);
        $app = $appCon->get($can->getVapplication());
        $this->properties = ['uname' => strtoupper($_COOKIE['uname'])];
        $arr = $this->formPageChecker($app);
        $this->properties = array_merge($this->properties, $arr);
        return $this->render($this->properties['path'], $this->properties);
    }

    /**
     * @return string url of the form to be filled.
     * @param \sms\domain\Application $app
     */
    private function formPageChecker(Application $app): array {
        if ($app->getVapplication()) {
            return ['path' => 'application/bioinfo.twig', 'link' => '/applicant/bioinfo/new', 'command' => "Continue Application"];
        } elseif ($app->getVguardian_info() == null) {
            return ['path' => 'application/guardianinfo.twig', 'link' => '/applicant/guardian/new', 'command' => "Continue Application"];
        } elseif ($app->getVeducation_info() == null) {
            return ['path' => 'application/educationinfo.twig', 'link' => '/applicant/education/new', 'command' => "Continue Application"];
        }
    }

    public function logout() {
        session_start();
        unset($_COOKIE['uname']);
        unset($_COOKIE['app']);
        //echo session_status();
        if (session_destroy()) {
            return $this->render('application/login.twig', []);
        }
    }

    public function aoLogout() {
        session_start();
        unset($_COOKIE['uname']);
        //echo session_status();
        if (session_destroy()) {
            //header('Location: /admission/logout');
            return $this->render('admission/aologout.twig', ['msg' => 'You have successfully logged out.']);
        }
    }
    public function hrLogout() {
        session_start();
        unset($_COOKIE['uname']);
        //echo session_status();
        if (session_destroy()) {
            //header('Location: /admission/logout');
            return $this->render('hr/hrlogout.twig', ['msg' => 'You have successfully logged out.']);
        }
    }
    
    public function mgtLogout() {
        session_start();
        unset($_COOKIE['uname']);
        //echo session_status();
        if (session_destroy()) {
            //header('Location: /admission/logout');
            return $this->render('management/mgtlogout.twig', ['msg' => 'You have successfully logged out.']);
        }
    }
    

}
