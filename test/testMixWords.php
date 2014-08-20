<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require dirname(__DIR__) . '/src/initialize.php';

$manager = new WordsManager();

$words = $manager->extractString('cửa lò');
$mixs = $manager->mixWords($words[0], $words[1]);
foreach ($mixs as $mix) {
    echo $mix[0] , ' ', $mix[1], PHP_EOL;
}

