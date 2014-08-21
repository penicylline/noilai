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

//define('DAU_NGANG', 'ngang');
//define('DAU_HUYEN', 'huyen');
//define('DAU_SAC', 'sac');
//define('DAU_HOI', 'hoi');
//define('DAU_NGA', 'nga');
//define('DAU_NANG', 'nang');

function __autoload($className) {
    $file = strtolower($className);
    if (file_exists(__DIR__ . '/core/' . $file . '.php')) {
        include __DIR__ . '/core/' . $file . '.php';
    }
    if (file_exists(__DIR__ . '/plugins/' . $file . '.php')) {
        include __DIR__ . '/plugins/' . $file . '.php';
    }
}