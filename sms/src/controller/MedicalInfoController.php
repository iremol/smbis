<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\MedicalInfo;
use sms\model\MedicalInfoModel;
use sms\util\UniqueId;

/**
 * Description of MedicalInfoController
 *
 * @author root
 */
class MedicalInfoController extends AbstractController {

    public function persist() {
        $result = false;
        $medicalInfo = new MedicalInfo();
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $properties = ['uname' => $cookies->get('uname')];
        $unique = new UniqueId();
        $medicalInfo->setVMedical_Info($unique->generateId());
        $medicalInfo->setVConditions($params->getString('vconditions'));

        //$appCont = new ApplicationController($this->di, $this->request);


        $model = new MedicalInfoModel($this->db["db_applicant"]);
        $result = $model->persist($medicalInfo);
        return $result;
//        if ($result) {
//            $appCont = new ApplicationController($this->di, $this->request);
//            $app = $appCont->get($cookies->getString('app'));
//            $app->setVeducation_info($medicalInfo->getVeducation_info());
//            if ($appCont->update($app)) {
//                return $this->render('application/sibling.twig', $properties);
//            } else {
//                return $this->render('error.twig', ['errorMessage' => 'Could not update Application Table']);
//            }
//        } else {
//            return $this->render('error.twig', []);
//        }
    }
    
    public function get(string $vmedical_info): MedicalInfo {
        $model = new MedicalInfoModel($this->db["db_applicant"]);
        return $model->get($vmedical_info);
    }

}
