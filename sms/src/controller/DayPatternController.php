<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\model\DayPatternModel;

/**
 * Description of DayPatternController
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class DayPatternController extends AbstractController{
    public function getAll() {
        $model = new DayPatternModel($this->db['db_mgt']);
        return $model->getAll();
    }
}
