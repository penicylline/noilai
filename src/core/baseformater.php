<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author hoai.bui
 */
abstract class BaseFormater {
    public $priority = 1;
    public abstract function format($phuamdau, $van, $dau, $phuamcuoi);
}
