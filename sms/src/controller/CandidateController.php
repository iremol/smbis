<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Candidate;
use sms\exception\NotFoundException;
use sms\model\CandidateModel;
use sms\util\UniqueId;

/**
 * Description of CandidateController
 *
 * @author root
 */
class CandidateController extends AbstractController {

    public function persist(string $vusername, string $vapplication): bool {
        $Id = new UniqueId();
        $candidate = new Candidate();
        $candidate->setVcandidate($Id->generateCandidateId());
        $candidate->setVusername($vusername);
        $candidate->setVapplication($vapplication);

        $model = new CandidateModel($this->db["db_applicant"]);
        return $model->persist($candidate) ? "success" : "failure";
    }

    public function showNew() {
        return $this->render('application/guardianinfo.twig', []);
    }

    public function get(): Candidate {
//        echo $_COOKIE['uname'];
        if (isset($_SESSION['uname'])) {
            $model = new CandidateModel($this->db["db_applicant"]);
            return $model->get($_SESSION['uname']);
        }
    }

    public function getAll() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            return $this->render('admission/logout', []);
        }
        $model = new CandidateModel($this->db);
        $candidates = $model->getAll();

//        print_r($candidates);
//        exit;
        $arr = ['candidates' => $candidates, 'uname' => $_SESSION['uname']];
        return $this->render('admission/candidates.twig', $arr);
    }

    public function getAllGranted() {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
//            return $this->render('/admission/logout', []);
        }
        $model = new CandidateModel($this->db["db_admission"]);
        try {
            $candidates = $model->getAllGranted();
        } catch (NotFoundException $e) {
            $err = $e->getMessage();
        }

//        print_r($candidates);
//        exit;
        $arr = ['candidates' => $candidates, 'uname' => $_SESSION['uname'], 'msg' => $err];
        return $this->render('admission/candidates_granted.twig', $arr);
    }

    public function getAllNoAdmission(string $msg = "") {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('/admission/logout', []);
        }
        $model = new CandidateModel($this->db["db_admission"]);
        $candidates = $model->getAllNoAdmission();

//        print_r($candidates);
//        exit;
        $arr = ['candidates' => $candidates, 'uname' => $_SESSION['uname'], 'msg' => $msg];
        return $this->render('admission/candidates.twig', $arr);
    }

    public function updateAdmissionGranted(string $vcandidate): string {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('/admission/logout', []);
        }
        $model = new CandidateModel($this->db["db_admission"]);
        $sucess = $model->updateAdmissionGranted($vcandidate);
        if ($success) {
            return $this->getAllNoAdmission('Update Failed');
        } else {
            //return $this->getAllNoAdmission('Update Successful');
            header("Location: /admission/c/view");
        }
    }

    public function updateAdmissionDenied(string $vcandidate): string {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            //return $this->render('/admission/logout', []);
        }
        $model = new CandidateModel($this->db["db_admission"]);
        $sucess = $model->updateAdmissionDenied($vcandidate);
        if ($success) {
            return $this->getAllNoAdmission('Update Failed');
        } else {
            //return  $this->getAllNoAdmission('Update Successful');
            header("Location: /admission/c/view");
        }
    }

    public function update(string $app) {
        session_start();
        if (!isset($_SESSION['uname'])) {
            header('location: /admission/logout');
            return $this->render('/admission/logout', []);
        }

        $model = new CandidateModel($this->db["db_admission"]);
        ($model->update($app));
        return $this->getAllGranted();
    }

}
