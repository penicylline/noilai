<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require dirname(__DIR__) . '/src/initialize.php';

$manager = new WordsManager();

var_dump($manager->extractString('Tuyệt đối không được lấy ô và giầy của người khác để dùng giết'));
var_dump($manager->extractString('Giá gì gió'));

