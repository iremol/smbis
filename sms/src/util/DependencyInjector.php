<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace sms\util;

use sms\exception\NotFoundException;

/**
 * Description of DependencyInjector
 *
 * @author root
 */
class DependencyInjector {

    private $dependencies = [];

    public function set(string $name, $object) {
        $this->dependencies[$name] = $object;
    }

    public function get(string $name) {
        if (isset($this->dependencies[$name])) {
            return $this->dependencies[$name];
        }
        throw new NotFoundException($name . ' dependency not found.');
    }

}
