<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\model\ClassDurationModel;

/**
 * Description of ClassDurationController
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 */
class ClassDurationController extends AbstractController {
    
    /**
     * @access public
     * @return array
     */
    public function getAll() : array{
        return (new ClassDurationModel($this->db['db_mgt']))->getAll(); // returns all records available in the sys_class_duratn relation
    }
}
