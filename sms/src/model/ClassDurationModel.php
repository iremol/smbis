<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\model;

use PDO;

/**
 * Description of ClassDurationModel
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 * #@author root
 */
class ClassDurationModel extends AbstractModel {

    const CLASSNAME = '\sms\domain\ClassDuration';
    //const INSRTSTMT = "insert into teaching.class (vclass, vclassteacher, vclassname,vclassroom) values(?,?,?,?)";
    const SELSTMT = 'select * from predef.sys_class_duratn where bsid = ?';
    
    /**
     * 
     * @param int $page
     * @param int $pageLength
     * @return array
     */
    public function getAll(int $page=0, int $pageLength=0): array {
        $stmt = $this->db->prepare("select vdesc, bsid, siduration from predef.sys_class_duratn");
        $result = $stmt->execute();
        if (!$result) {
            print_r($stmt->errorInfo());
        }
        $m = $stmt->fetchAll(PDO::FETCH_CLASS, self::CLASSNAME);
        return $m;
    }


}
