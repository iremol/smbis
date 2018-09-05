<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\model\CountriesModel;

/**
 * Description of CountriesController
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 */
class CountriesController extends AbstractController {

    /**
     * @name getALl returns all the codes and names of all countries.
     * @return array
     */
    public function getAll(): array {
        $model = new CountriesModel($this->db["db_mgt"]);
        return $model->getAll();
    }

}
