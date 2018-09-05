<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\util;

use sms\controller\AbstractController;
use sms\exception\NotFoundException;

/**
 * Description of Tools
 * @author Imole Akpobome<imole.akpobome@gmail.com>
 */
class Tools {

    public static function uploadFile(string $name): string {
        $target_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/";
        //$target_dir = "../../uploads/";
        $target_file = $target_dir . basename($_FILES["$name"]["name"]);

        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
// Check if image file is a actual image or fake image
        if (isset($_POST["submit"])) {
            $check = getimagesize($_FILES["$name"]["tmp_name"]);
            if ($check !== false) {
                echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            } else {
                echo "File is not an image.";
                $uploadOk = 0;
            }
        }
// Check if file already exists
        if (file_exists($target_file)) {
            echo "Sorry, file already exists.";
            $uploadOk = 0;
        }
// Check file size
        if ($_FILES["$name"]["size"] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
// Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            $uploadOk = 0;
        }
// Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
// if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES["$name"]["tmp_name"], $target_file)) {
                return '../../uploads/' . $_FILES["$name"]["name"]; // $target_file;
                //echo "The file " . basename($_FILES["$name"]["name"]) . " has been uploaded.";
            } else {
                throw new NotFoundException("Sorry");
                //echo "Sorry, there was an error uploading your file.";
            }
        }
    }
    
}
