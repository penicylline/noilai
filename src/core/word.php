<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of word
 *
 * @author hoai.bui
 */
class Word {
    public $phuamdau, $van, $dau, $phuamcuoi;
    
    private $formater, $validators = array();
    
    private $error = null;
    
    public function __construct($phuamdau = '', $van = '', $dau = 0, $phuamcuoi = '')
    {
        $this->phuamdau = $phuamdau;
        $this->van = $van;
        $this->dau = $dau;
        $this->phuamcuoi = $phuamcuoi;
    }

    public function setFormater(BaseFormater $formater)
    {
        $this->formater = $formater;
    }
    
    public function setValidators($validators)
    {
        $this->validators = $validators;
    }
    
    public function addValidator($validator)
    {
        $this->validators[] = $validator;
    }
    
    public function validate()
    {
        foreach ($this->validators as &$validator) {
            $error = $validator->validate($this);
            if ($error) {
                return $error;
            }
        }
    }
    
    public function setError($error)
    {
        $this->error = $error;
    }
    
    public function getError()
    {
        return $this->error;
    }
    
    public function __toString() {
        if (!is_null($this->formater)) {
            return $this->formater->format($this->phuamdau, $this->van, $this->dau, $this->phuamcuoi);
        }
        return '';
    }
}
