<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require dirname(__DIR__) . '/src/initialize.php';

$wordExtractor = new WordExtractor();
$validator = new WordValidator();

var_dump($validator->validate($wordExtractor->extract('chuẩn')));
var_dump($validator->validate($wordExtractor->extract('truyết')));
var_dump($validator->validate($wordExtractor->extract('gì')));
var_dump($validator->validate($wordExtractor->extract('giết')));
var_dump($validator->validate($wordExtractor->extract('chíén')));
