<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of formater
 *
 * @author hoai.bui
 */
class WordFormater extends BaseFormater{
    public function format($phuamdau, $van, $dau, $phuamcuoi)
    {
        $vanCodau = $van;
        $vanLen = mb_strlen($van, 'UTF-8');
        
        if (in_array($phuamcuoi, WordsManager::$phuamtrac) && ($dau != DAU_SAC || $dau != DAU_NANG)) {
            $dau = DAU_SAC;
        }
        if ($vanLen == 1) {
            $vanPos = array_search($van, WordsManager::$nguyenam);
            $vanCodau = WordsManager::$listdau[$dau][$vanPos];
        }
        if ($vanLen == 2) {
            $posDau = strlen($phuamcuoi) == 0 ? 0 : 1;
            $nguyenAmCoDau = $this->getMbCharAt($van, $posDau);
            $vanPos = array_search($nguyenAmCoDau, WordsManager::$nguyenam);
            if ($posDau == 0) {
                $vanCodau = WordsManager::$listdau[$dau][$vanPos] . $this->getMbCharAt($van, 1);
            } else {
                $vanCodau = $this->getMbCharAt($van, 0) . WordsManager::$listdau[$dau][$vanPos];
            }
        }
        if ($vanLen == 3) {
            $posDau = strlen($phuamcuoi) == 0 ? 1 : 2;
            $nguyenAmCoDau = $this->getMbCharAt($van. $posDau);
            $vanPos = array_search($nguyenAmCoDau, WordsManager::$nguyenam);
            if ($posDau == 1) {
                $vanCodau = $this->getMbCharAt($van, 0) . WordsManager::$listdau[$dau][$vanPos] . $this->getMbCharAt($van, 2);
            } else {
                $vanCodau = mb_substr($van, 0, 2, 'UTF-8') . WordsManager::$listdau[$dau][$vanPos];
            }
        }
        $van0 = $this->getMbCharAt($van, 0);
        if ($van0 == 'e' || $van0 == 'ê' || $van0 == 'i') {
            if ($phuamdau == 'c' || $phuamdau == 'q') {
                $phuamdau = 'k';
            }
            if ($phuamdau == 'g') {
                $phuamdau = 'gh';
            }
            if ($phuamdau == 'ng') {
                $phuamdau = 'ngh';
            }
        }
        if ($phuamdau == 'gi' && $van0 == 'i') {
            $phuamdau = 'g';
        }
        return $phuamdau . $vanCodau . $phuamcuoi;
    }
    
    private function getMbCharAt($string, $pos)
    {
        return mb_substr($string, $pos, 1, 'UTF-8');
    }
}
