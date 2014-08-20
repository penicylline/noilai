<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of wordsmanager
 *
 * @author hoai.bui
 */

class WordsManager {
    public static $nguyenam = ['a', 'ă', 'â', 'e', 'ê', 'i', 'o', 'ô', 'ơ', 'u', 'ư', 'y'];
    public static $nguyenamdoi = ['oo', 'iê', 'ươ', 'oe', 'ai', 'au', 'ua', 'ưa', 'ưu', 'âu', 'uâ', 'ia', 'ui', 'ưi', 'iu', 'êu',
                    ' oi', 'ôi', 'ơi', 'ay', 'ây', 'uy', 'uô', 'uâ', 'oa', 'oă', 'ao', 'eo', 'uê'];
    public static $nguyenamba = ['uya', 'ươi', 'uyê', 'iêu', 'oai', 'oay', 'uây', 'uôi', 'ươu'];
    public static $nguyenamdonle = ['uya', 'ươi', 'iêu', 'oai', 'uây', 'uôi', 'ươu', 'eo', 'ao', 'ây','ơi', 'ôi', 
                        'oi', 'êu', 'iu', 'ưi', 'ia', 'âu', 'ưu', 'ưa', 'ua', 'au', 'ai', 'ay'];

    public static $nguyenamtudo = ['oa', 'oe', 'uy', 'uê']; // các nguyên âm có hoặc ko có phụ âm đi sau

    public static $listphuam = ['ngh', 'ng', 'nh', 'ch', 'tr', 'ph', 'kh', 'th', 'gh', 'gi', 'qu',
        'b', 'c', 'd', 'đ', 'g', 'h', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x'];

    public static $listphuamcuoi = ['ng', 'nh', 'ch', 'c', 'm', 'n', 'p', 't'];
    public static $phuamtrac = ['c', 't', 'p', 'ch']; //nguyen am truoc phu am nay luon mang dau sac hoac nang

    public static $listdau = [
        DAU_HUYEN => ['à','ằ','ầ','è', 'ề', 'ì', 'ò', 'ồ', 'ờ', 'ù', 'ừ', 'ỳ'],
        DAU_SAC => ['á','ắ','ấ','é', 'ế', 'í', 'ó', 'ố', 'ớ', 'ú', 'ứ', 'ý'],
        DAU_HOI => ['ả','ẳ','ẩ','ẻ', 'ể', 'ỉ', 'ỏ', 'ổ', 'ở', 'ủ', 'ử', 'ỷ'],
        DAU_NGA => ['ã','ẵ','ẫ','ẽ', 'ễ', 'ĩ', 'õ', 'ỗ', 'ỡ', 'ũ', 'ữ', 'ỹ'],
        DAU_NANG => ['ạ','ặ','ậ','ẹ', 'ệ', 'ị', 'ọ', 'ộ', 'ợ', 'ụ', 'ự', 'ỵ'],
        DAU_NGANG => ['a', 'ă', 'â', 'e', 'ê', 'i', 'o', 'ô', 'ơ', 'u', 'ư', 'y'],
    ];
    
    private $formater;
    
    public function __construct()
    {
        $this->formater = new WordFormater();
    }
    
    public function extractString($phrase)
    {
        $phrase = preg_replace('!\s+!', ' ', trim($phrase));
        $phrase = mb_strtolower($phrase, 'UTF-8');
        $strings = explode(' ', $phrase);
        $wordExtractor = new WordExtractor();
        $validator = new WordValidator();
        $words = array();
        foreach ($strings as &$string) {
            $word = $wordExtractor->extract($string);
            $error = $validator->validate($word);
            if ($error) {
                return $error;
            }
            $words[] = $word;
        }
        return $words;
    }
    
    public function mixWords(Word $word1, Word $word2)
    {
        $validator = new WordValidator();
        $words = array();
        
        $w1 = $this->createWord($word1->phuamdau, $word1->van, $word2->dau, $word1->phuamcuoi);
        $w2 = $this->createWord($word2->phuamdau, $word2->van, $word1->dau, $word2->phuamcuoi);
        if ($validator->validate($w1) == null && $validator->validate($w2) == null) {
            $words[] = array($w1, $w2);
        }
        
        $w1 = $this->createWord($word1->phuamdau, $word2->van, $word2->dau, $word1->phuamcuoi);
        $w2 = $this->createWord($word2->phuamdau, $word1->van, $word1->dau, $word2->phuamcuoi);
        if ($validator->validate($w1) == null && $validator->validate($w2) == null) {
            $words[] = array($w1, $w2);
        }
        
        $w1 = $this->createWord($word1->phuamdau, $word2->van, $word1->dau, $word1->phuamcuoi);
        $w2 = $this->createWord($word2->phuamdau, $word1->van, $word2->dau, $word2->phuamcuoi);
        if ($validator->validate($w1) == null && $validator->validate($w2) == null) {
            $words[] = array($w1, $w2);
        }
        
        $w1 = $this->createWord($word1->phuamdau, $word1->van, $word2->dau, $word2->phuamcuoi);
        $w2 = $this->createWord($word2->phuamdau, $word2->van, $word1->dau, $word1->phuamcuoi);
        if ($validator->validate($w1) == null && $validator->validate($w2) == null) {
            $words[] = array($w1, $w2);
        }
        
        $w1 = $this->createWord($word1->phuamdau, $word2->van, $word2->dau, $word2->phuamcuoi);
        $w2 = $this->createWord($word2->phuamdau, $word1->van, $word1->dau, $word1->phuamcuoi);
        if ($validator->validate($w1) == null && $validator->validate($w2) == null) {
            $words[] = array($w1, $w2);
        }
        
        $w1 = $this->createWord($word1->phuamdau, $word2->van, $word1->dau, $word2->phuamcuoi);
        $w2 = $this->createWord($word2->phuamdau, $word1->van, $word2->dau, $word1->phuamcuoi);
        if ($validator->validate($w1) == null && $validator->validate($w2) == null) {
            $words[] = array($w1, $w2);
        }
        
        $w1 = $this->createWord($word1->phuamdau, $word1->van, $word1->dau, $word2->phuamcuoi);
        $w2 = $this->createWord($word2->phuamdau, $word2->van, $word2->dau, $word1->phuamcuoi);
        if ($validator->validate($w1) == null && $validator->validate($w2) == null) {
            $words[] = array($w1, $w2);
        }
        
        return $words;
    }
    
    public function createWord($phuamdau, $van, $dau, $phuamcuoi)
    {
        $word = new Word($phuamdau, $van, $dau, $phuamcuoi);
        $word->setFormater($this->formater);
        return $word;
    }
}
