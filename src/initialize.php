<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

define('DAU_NGANG', 0);
define('DAU_HUYEN', 1);
define('DAU_SAC', 2);
define('DAU_HOI', 3);
define('DAU_NGA', 4);
define('DAU_NANG', 5);

function __autoload($className) {
    $file = strtolower($className);
    if (file_exists(__DIR__ . '/core/' . $file . '.php')) {
        include __DIR__ . '/core/' . $file . '.php';
    }
    if (file_exists(__DIR__ . '/plugins/' . $file . '.php')) {
        include __DIR__ . '/plugins/' . $file . '.php';
    }
}