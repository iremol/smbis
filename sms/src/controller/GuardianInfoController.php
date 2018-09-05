<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\GuardianInfo;
use sms\model\GuardianInfoModel;
use sms\util\UniqueId;

/**
 * Description of GuardianInfoController
 *
 * @author root
 */
class GuardianInfoController extends AbstractController {

    public function persist() {
        
        $result = false;
        $params = $this->request->getParams();
        $cookies = $this->request->getCookies();
        $properties = ['uname' => $cookies->get('uname')];
        $unique = new UniqueId();
        $guardianInfo = new GuardianInfo();
        $guardianInfo->setVguardian_info(strtoupper($unique->generateId()));
        $guardianInfo->setVtitle(strtoupper($params->getString('vtitle')));
        $guardianInfo->setVsurname(strtoupper($params->getString('vsurname')));
        $guardianInfo->setVfirstname(strtoupper($params->getString('vfirstname')));
        $guardianInfo->setVothername(strtoupper($params->getString('vothernames')));
        $guardianInfo->setVaddress($params->getString('vaddress'));
        $guardianInfo->setVpostcode($params->getString('vpostcode'));
        $guardianInfo->setVemail($params->getString('vemail'));
        $guardianInfo->setVdaytime_no($params->getString("vdaytime_no"));
        $guardianInfo->setVrelationship($params->getString("vrelationship"));
        $model = new GuardianInfoModel($this->db["db_applicant"]);
        $result = $model->persist($guardianInfo);
        
        if ($result) {
            $appCont = new ApplicationController($this->di, $this->request);
            $app = $appCont->get($cookies->getString('app'));
            $app->setVguardian_info($guardianInfo->getVguardian_info());
            if ($appCont->update($app)) {
                echo 'I got here';
                return $this->render('application/educationinfo.twig', $properties);
            } else {
                return $this->render('error.twig', ['errorMessage' => 'Could not update Application Table']);
            }
        } else {
            return $this->render('error.twig', []);
        }
    }

    public function showNew() {
        return $this->render('application/guardianinfo.twig', []);
    }
    
    public function get(string $vguardian_info): GuardianInfo{
        $model = new GuardianInfoModel($this->db["db_applicant"]);
        return $model->get($vguardian_info);
    }

}
