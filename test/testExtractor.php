<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require dirname(__DIR__) . '/src/initialize.php';

$wordExtractor = new WordExtractor();

var_dump($wordExtractor->extract('chuan'));
var_dump($wordExtractor->extract('truyết'));
var_dump($wordExtractor->extract('gì'));
var_dump($wordExtractor->extract('giết'));
var_dump($wordExtractor->extract('chíén'));
