<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;

/**
 * Description of DayPatternModel
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class DayPatternModel  extends AbstractModel{ 
    const CLASSNAME = "\sms\domain\DayPattern";
    const SELSTMT = "select * from teaching.daypattern";
    
     public function getAll(int $page=0, int $pageLength=0): array {
        $stmt = $this->db->prepare(self::SELSTMT);
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m;
    }
}
