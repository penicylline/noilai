<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require dirname(__DIR__) . '/src/initialize.php';

$formater = new WordFormater();
var_dump($formater->format('gi', 'i', DAU_HUYEN, ''));
var_dump($formater->format('gi', 'iêt', DAU_NGANG, ''));
var_dump($formater->format('d', 'ê', DAU_HOI, ''));
var_dump($formater->format('n', 'ươ', DAU_SAC, 'c'));
var_dump($formater->format('t', 'uyê', DAU_SAC, 't'));
