<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ivalidator
 *
 * @author hoai.bui
 */
abstract class BaseValidator {
    public $priority = 1;
    public abstract function validate(Word $word);
}
