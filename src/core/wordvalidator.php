<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of validatetor
 *
 * @author hoai.bui
 */
class WordValidator extends BaseValidator {
    public function validate(Word $word)
    {
        if ($word->getError()) {
            return $word->getError();
        }
        $phuamdau = $word->phuamdau;
        $van = $word->van;
        $dau = $word->dau;
        $phuamcuoi = $word->phuamcuoi;
        $len = mb_strlen($van, 'UTF-8');
        if ($len == 0) {
            return 'Không có vần trong chữ';
        }
        if ($len > 3) {
            return 'Vần ' . $van . ' không hợp lệ';
        }
        
        if($len > 1) {
            if (!in_array($van, WordsManager::$nguyenamba)) {
                if (!in_array($van, WordsManager::$nguyenamdoi)){
                    return 'Vần ' . $van . ' không tồn tại';
                }
            }
        } else {
            if ($van == 'ă' && $phuamcuoi == '') {
                return 'Âm ă đơn lẻ không tồn tại';
            }
        }
        if (in_array($van, WordsManager::$nguyenamdonle)) {
            if (strlen($phuamcuoi) > 0) {
                return 'Âm ' . $van . $phuamcuoi . ' không tồn tại';
            }
        } else {
            if (($len > 1 && !in_array($van, WordsManager::$nguyenamtudo) )&& strlen($phuamcuoi) == 0) {
                return 'Vần ' . $van . ' không đứng riêng lẻ';
            }
        }
        
        if ($dau != DAU_SAC && $dau != DAU_NANG  && in_array($phuamcuoi, WordsManager::$phuamtrac)) {
            return 'Vần ' . $van . $phuamcuoi . ' phải đi kèm dấu sắc hoặc nặng';
        }
        return null;
    }
}
