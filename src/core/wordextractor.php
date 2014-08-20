<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of wordextractor
 *
 * @author hoai.bui
 */
class WordExtractor {
    
    public function extract($string)
    {
        $dau = DAU_NGANG;
        $van = $phuamdau = $phuamcuoi = '';
        
        $len = mb_strlen($string, 'UTF-8');
        for ($i = 0; $i < $len; $i++) {
            $wordChar = mb_substr($string, $i, 1, 'UTF-8');
            foreach(WordsManager::$listdau as $thanh => &$chars) {
                foreach($chars as $pos => &$char) {
                    if($char == $wordChar) {
                        if ($thanh == DAU_NGANG) {
                            continue;
                        } else {
                            if ($dau == DAU_NGANG) {
                                $dau = $thanh;
                            } else {
                                $word = new Word();
                                $word->setError('Qua nhieu dau trong chu ' . $string);
                                return $word;
                            }
                        }
                        $before = $i == 0 ? '' : mb_substr($string, 0, $i, 'UTF-8');
                        $after = $i == $len - 1 ? '' : mb_substr($string, $i + 1, $len - $i - 1, 'UTF-8');
                        $string = $before . WordsManager::$nguyenam[$pos] . $after;
                        break;
                    }
                }
            }
        }
        
        foreach(WordsManager::$listphuam as $phuam) {
            if (empty($phuamdau) && mb_strpos($string, $phuam, 0, 'UTF-8') === 0) {
                $phuamdau = $phuam;
            }
        }
        
        foreach(WordsManager::$listphuamcuoi as $phuam) {
            if(empty($phuamcuoi) && mb_strlen($phuam, 'UTF-8') < $len && substr($string, -mb_strlen($phuam, 'UTF-8')) === $phuam) {
                $phuamcuoi = $phuam;
            }
        }

        $wordLen = strlen($string);
        $phuamdaulen = strlen($phuamdau);
        $phuamcuoilen = strlen($phuamcuoi);
        if ($wordLen - $phuamdaulen -  $phuamcuoilen> 0) {
            $van = substr($string, $phuamdaulen, $wordLen - $phuamdaulen - $phuamcuoilen);
        }
        if ($phuamdau == 'gi') {
            if (strlen($van) == 0) {
                $van = 'i';
            }else {
                if (mb_substr($van, 0, 1, 'UTF-8') == 'ê' && (strlen($van > 0) || $phuamcuoilen > 0)) {
                    $van = 'i' . $van;
                }
            }
        }
        
        return new Word($phuamdau, $van, $dau, $phuamcuoi);
    }
}
