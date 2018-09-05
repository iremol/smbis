<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Application;
use sms\domain\BioInfo;
use sms\domain\Candidate;
use sms\domain\EducationInfo;
use sms\domain\GuardianInfo;
use sms\domain\Login;
use sms\domain\MedicalInfo;
use sms\exception\DbException;
use sms\exception\NotFoundException;
use sms\model\ApplicationModel;
use sms\model\BioInfoModel;
use sms\model\CandidateModel;
use sms\model\EducationInfoModel;
use sms\model\GuardianInfoModel;
use sms\model\LoginModel;
use sms\model\MedicalInfoModel;
use sms\util\UniqueId;

/**
 * Description of ApplicationController
 *
 * @author root
 */
class ApplicationController extends AbstractController {

    private function persistApplication(BioInfo $bioInfo, GuardianInfo $guardianInfo, EducationInfo $eduInfo, MedicalInfo $medInfo): Application {
        $id = new UniqueId();
        $application = new Application();
        $application->setVapplication($id->generateApplicationId());
        $application->setDdateofapplication(date('Y/m/d G:i:s'));
        $application->setVbio_info($bioInfo->getVbio_info());
        $application->setVeducation_info($eduInfo->getVeducation_info());
        $application->setVguardian_info($guardianInfo->getVguardian_info());
        $application->setVmedical_info($medInfo->getVMedical_Info());
        $model = new ApplicationModel($this->db["db_applicant"]);
        $result = $model->persist($application);
        return $application;
    }

    private function persistCandidate(string $vusername, string $vapplication): Candidate {
        $Id = new UniqueId();
        $candidate = new Candidate();
        $candidate->setVcandidate($Id->generateCandidateId());
        $candidate->setVusername($vusername);
        $candidate->setVapplication($vapplication);

        $model = new CandidateModel($this->db["db_applicant"]);
        $result = $model->persist($candidate);
        return $candidate;
    }

    public function persist(): string {
        try {
            $login = $this->persistLogin();
            $bioInfo = $this->persistBioInfo();
            $guardianInfo = $this->persistGuardianInfo();
            $eduInfo = $this->persistEducationInfo();
            $medInfo = $this->persistMedicalInfo();
            $application = $this->persistApplication($bioInfo, $guardianInfo, $eduInfo, $medInfo);
            $candidate = $this->persistCandidate($login->getVusername(), $application->getVapplication());
        } catch (DbException $e) {
            $this->log->critical('[ ' . $_SESSION['uname'] . ' ]' . "[ $e ]");
            $this->render('application/application.twig', ["msg" => "Your Application was not successful"]);
        }
        return $this->render('application/home.twig', ["msg" => "Your Application was successful."]) ;
        //return $candidate != null ? true : false;
    }

    private function persistLogin(): Login {
        $login = new Login();
        $params = $this->request->getParams();
        $login->setVusername($params->getString('username'));
        $login->setVpassword(password_hash($params->getString('password'), PASSWORD_DEFAULT));

        $model = new LoginModel($this->db["db_applicant"]);
        $result = $model->persist($login);
        return $login;
    }

    private function persistBioInfo(): BioInfo {
        $result = false;
        $bioInfo = new BioInfo();
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $unique = new UniqueId();
//        echo $params->getString('vbisurname');
//        exit();
        $bioInfo->setVbio_info($unique->generateId());
        $bioInfo->setVsurname($params->getString('vbisurname'));
        $bioInfo->setVfirstname($params->getString('vbifirstname'));
        $bioInfo->setVothernames($params->getString('vbiothernames'));
        $bioInfo->setVgender($params->getString('vgender'));
        $bioInfo->setDdob($params->getString('ddob'));
        $bioInfo->setVaddress($params->getString('vbiaddress'));
        $bioInfo->setVpic($this->uploadFile());


//$appCont = new ApplicationController($this->di, $this->request);


        $model = new BioInfoModel($this->db["db_applicant"]);
        $result = $model->persist($bioInfo);
        return $bioInfo;
    }

    private function persistGuardianInfo(): GuardianInfo {

        $result = false;
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $unique = new UniqueId();
        $guardianInfo = new GuardianInfo();
        $guardianInfo->setVguardian_info($unique->generateId());
        $guardianInfo->setVtitle($params->getString('vtitle'));
        $guardianInfo->setVsurname($params->getString('vsurname'));
        $guardianInfo->setVfirstname($params->getString('vfirstname'));
        $guardianInfo->setVothername($params->getString('vothernames'));
        $guardianInfo->setVaddress($params->getString('vaddress'));
        $guardianInfo->setVpostcode($params->getString('vpostcode'));
        $guardianInfo->setVemail($params->getString('vemail'));
        $guardianInfo->setVdaytime_no($params->getString("vdaytime_no"));
        $guardianInfo->setVrelationship($params->getString("vrelationship"));
        $model = new GuardianInfoModel($this->db["db_applicant"]);
        $result = $model->persist($guardianInfo);
        return $guardianInfo;
    }

    private function persistEducationInfo() {
        $educationInfo = new EducationInfo();
        $params = $this->request->getParams();
        $unique = new UniqueId();
        $educationInfo->setVeducation_info($unique->generateId());
        $educationInfo->setVtrainer_name($params->getString('vtrainer_name'));
        $educationInfo->setVphone_num($params->getString('vphone_num'));
        $educationInfo->setVaddress($params->getString('vaddress'));
//$appCont = new ApplicationController($this->di, $this->request);


        $model = new EducationInfoModel($this->db["db_applicant"]);
        $result = $model->persist($educationInfo);
        if (!$result)
            echo "Error";
        return $educationInfo;
    }

    private function persistMedicalInfo() {
        $result = false;
        $medicalInfo = new MedicalInfo();
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $unique = new UniqueId();
        $medicalInfo->setVMedical_Info($unique->generateId());
        $medicalInfo->setVConditions($params->getString('vconditions'));

//$appCont = new ApplicationController($this->di, $this->request);


        $model = new MedicalInfoModel($this->db["db_applicant"]);
        $result = $model->persist($medicalInfo);
        return $medicalInfo;
    }

    public function showNew() {
        $arr = [];

        return $this->render('application/application.twig', ['path' => 'application/application.twig', 'link' => '/application/new', 'command' => "New Application"]);
    }

    public function get(string $app) {
        $model = new ApplicationModel($this->db["db_applicant"]);
        return $model->get($app);
    }

    private function update(Application $app) {
        $model = new ApplicationModel($this->db["db_applicant"]);
        return $model->update($app);
    }

    private function uploadFile(): string {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
//$target_dir = "../../uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["fileToUpload"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
                return '../../uploads/' . $_FILES["fileToUpload"]["name"]; // $target_file;
//echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                throw new NotFoundException("Sorry");
//echo "Sorry, there was an error uploading your file.";
            }
        }
    }

    public function viewDetail() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render('error.twig', []);
        }
        $app = $this->get($_COOKIE['app']);
        $bio = (new BioInfoController($this->di, $this->request))->get($app->getVbio_info());
        $gua = (new GuardianInfoController($this->di, $this->request))->get($app->getVguardian_info());
        $edu = (new EducationInfoController($this->di, $this->request))->get($app->getVeducation_info());
        $med = (new MedicalInfoController($this->di, $this->request))->get($app->getVmedical_info());
        $properties = ['bio' => $bio, 'gua' => $gua, 'edu' => $edu, 'med' => $med, 'uname' => strtoupper($_SESSION["uname"])];
        return $this->render('application/viewer.twig', $properties);
    }

    public function viewForAoDetail($appCon) {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render('aoerror.twig', []);
        }
//$app=$this->get($_COOKIE['app']);
        $app = $this->get($appCon);
        $bio = (new BioInfoController($this->di, $this->request))->get($app->getVbio_info());
        $gua = (new GuardianInfoController($this->di, $this->request))->get($app->getVguardian_info());
        $edu = (new EducationInfoController($this->di, $this->request))->get($app->getVeducation_info());
        $med = (new MedicalInfoController($this->di, $this->request))->get($app->getVmedical_info());
        $properties = ['bio' => $bio, 'gua' => $gua, 'edu' => $edu, 'med' => $med, 'uname' => strtoupper($_SESSION["uname"])];
        return $this->render('admission/view_candidate.twig', $properties);
    }

}
