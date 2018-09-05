<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;

/**
 * Description of CountriesModel
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class CountriesModel extends AbstractModel {
    const CLASSNAME ="\sms\domain\Countries";
    
    public function getAll() : array{
        $stmt = $this->db->prepare("select vcode, vname from facts.countries");
        if(!$stmt->execute()){
            print_r($stmt->errorInfo());
            return null;
        }
        else{
           return $arr =  $stmt->fetchAll(PDO::FETCH_CLASS,self::CLASSNAME);
        }
    }
}
