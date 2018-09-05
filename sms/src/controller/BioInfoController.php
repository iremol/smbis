<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\BioInfo;
use sms\exception\NotFoundException;
use sms\model\BioInfoModel;
use sms\util\UniqueId;

session_start();

/**
 * Description of BioInfoController
 *
 * @author root
 */
class BioInfoController extends AbstractController {

    public function persist() {
        $result = false;
        $bioInfo = new BioInfo();
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $properties = ['uname' => $cookies->get('uname')];
        $unique = new UniqueId();
        $bioInfo->setVbio_info(strtoupper($unique->generateId()));
        $bioInfo->setVsurname(strtoupper($params->getString('vsurname')));
        $bioInfo->setVfirstname(strtoupper($params->getString('vfirstname')));
        $bioInfo->setVothernames(strtoupper($params->getString('vothernames')));
        $bioInfo->setVgender($params->getString('vgender'));
        $bioInfo->setDdob($params->getString('ddob'));
        $bioInfo->setVaddress($params->getString('vaddress'));
        //$bioInfo->setVpic($params->getString('fileToUpload'));
        $bioInfo->setVpic($this->uploadFile());
        //echo $this->uploadFile();
        //exit();
        //$appCont = new ApplicationController($this->di, $this->request);


        $model = new BioInfoModel($this->db["db_applicant"]);
        $result = $model->persist($bioInfo);
        if ($result) {
            $appCont = new ApplicationController($this->di, $this->request);
            $app = $appCont->get($cookies->getString('app'));
            $app->setVbio_info($bioInfo->getVbio_info());
            if ($appCont->update($app)) {
                return $this->render('application/guardianinfo.twig', $properties);
            } else {
                return $this->render('error.twig', ['errorMessage' => 'Could not update Application Table']);
            }
        } else {
            return $this->render('error.twig', []);
        }
    }

    public function showNew() {
        return $this->render('application/bioinfo.twig', []);
    }

    public function get(String $vbio_info): BioInfo {
        $model = new BioInfoModel($this->db["db_applicant"]);
        return $model->get($vbio_info);
    }

    private function uploadFile(): string {
        $target_dir = "uploads/";
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
                return $target_file;
                echo "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
            } else {
                throw new NotFoundException("Sorry");
                echo "Sorry, there was an error uploading your file.";
            }
        }
    }

}
