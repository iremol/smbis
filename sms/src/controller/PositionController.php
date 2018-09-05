<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\controller;

use sms\domain\Position;
use sms\model\PositionModel;
use sms\util\UniqueId;

/**
 * Description of PositionController
 *
 * @author imole akpobome <imole.akpobome@gmail.com>
 * @version 1.0
 */
class PositionController extends AbstractController {
    /**
     * @author imole akpobome <imole.akpobome@gmail.com>
     * @name $persist
     * @return bool
     * 
     */
    public function persist(): string {
        $params = $this->request->getParams();
        $id = new UniqueId();
        $posId = strtoupper($id->generateId());
        $position  = new Position();
        $position->setVposition($posId);
        $position->setVdescription($params->getString("vdesc"));
        $model = new PositionModel($this->db['db_mgt']);
        return $model->persist($position) ? $this->render("management/createpos.twig", ["msg"=>"Successfully created"]) : $this->render("management/createpos.twig", ["msg"=>"Failed to create. contact administrator"]);
        
    }
    
    public function getAll(): array{
        $model = new PositionModel($this->db['db_human_res']);
        return $model->getAll();
    }

    /**
     * @access public
     * @return array
     */
    public function getTeachers() : array {
       return $model = (new PositionModel($this->db['db_human_res']))->getTeachers(); // All teachers are returned
  
        
    }
}
