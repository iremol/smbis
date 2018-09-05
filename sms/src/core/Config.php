<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\core;

use sms\exception\NotFoundException;

/**
 * Description of Config
 *
 * @author root
 */
class Config {
    private $data;
    private $msg;

    public function __construct() {
        $json = file_get_contents(__DIR__ . '/../../config/app.json');
        $this->data = json_decode($json, true);
        $this->msg = json_decode(file_get_contents(__DIR__.'/../../config/msg.json')); //Load and decode the content of the msg json file.
    }

    public function get($key) {
        if (!isset($this->data[$key])) {
            throw new NotFoundException("Key $key not in config.");
        }
        return $this->data[$key];
    }
    
    public function getMsg(string $key): string {
        if(!isset($msg[$key])){
            throw new NotFoundException("Key $key does not exist in msg file.");
        }
        return $this->msg[$key];
    }




}
