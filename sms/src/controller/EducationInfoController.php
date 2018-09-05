<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\EducationInfo;
use sms\model\EducationInfoModel;
use sms\util\UniqueId;

/**
 * Description of EducationInfoController
 *
 * @author root
 */
class EducationInfoController extends AbstractController {

    //put your code here
    public function persist() {
        $result = false;
        $educationInfo = new EducationInfo();
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $properties = ['uname' => $cookies->get('uname')];
        $unique = new UniqueId();
        $educationInfo->setVeducation_info($unique->generateId());
        $educationInfo->setVtrainer_name($params->getString('vtrainer_name'));
        $educationInfo->setVphone_num($params->getString('vphone_num'));
        $educationInfo->setVaddress($params->getString('vaddress'));
        //$appCont = new ApplicationController($this->di, $this->request);


        $model = new EducationInfoModel($this->db["db_applicant"]);
        $result = $model->persist($educationInfo);
        return $result;
//        if ($result) {
//            $appCont = new ApplicationController($this->di, $this->request);
//            $app = $appCont->get($cookies->getString('app'));
//            $app->setVeducation_info($educationInfo->getVeducation_info());
//            if ($appCont->update($app)) {
//                return $this->render('application/sibling.twig', $properties);
//            } else {
//                return $this->render('error.twig', ['errorMessage' => 'Could not update Application Table']);
//            }
//        } else {
//            return $this->render('error.twig', []);
//        }
    }

    public function get(string $education_info): EducationInfo {
        $model = new EducationInfoModel($this->db["db_applicant"]);
        return $model->get($education_info);
    }
}
