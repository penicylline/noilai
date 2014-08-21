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
    private $validators;
    private $extractor;


    public function __construct()
    {
        $this->formater = new WordFormater();
        $this->validators[] = new WordValidator();
        $this->extractor = new WordExtractor();
    }
    
    public function extractString($phrase)
    {
        $phrase = preg_replace('!\s+!', ' ', trim($phrase));
        $phrase = mb_strtolower($phrase, 'UTF-8');
        $strings = explode(' ', $phrase);
        $words = array();
        foreach ($strings as &$string) {
            $word = $this->extractor->extract($string);
            $word->setFormater($this->formater);
            $error = $this->validate($word);
            if ($error) {
                return $error;
            }
            $words[] = $word;
        }
        return $words;
    }
    
    public function mixWords(Word $word1, Word $word2)
    {
        $words = array();
        
        if (($word1->van == $word2->van && $word1->dau == $word2->dau && $word1->phuamcuoi == $word2->phuamcuoi)
                || ($word1->phuamdau == $word2->phuamdau && $word1->dau == $word2->dau)) {
            return $words;
        }
        
        $miz0 = $miz1 = array(); $miz2 = array(); $mizAll = array();
        if ($word1->van != $word2->van) {
            $miz0[] = array('van1' => $word1->van, 'van2' => $word2->van);
            $miz0[] = array('van1' => $word2->van, 'van2' => $word1->van);
        } else {
            $miz0[] = array('van1' => $word1->van, 'van2' => $word2->van);
        }
        
        if ($word1->dau != $word2->dau) {
            foreach ($miz0 as $mix) {
                $mix['dau1'] = $word1->dau;
                $mix['dau2'] = $word2->dau;
                $miz1[] = $mix;
                $mix['dau1'] = $word2->dau;
                $mix['dau2'] = $word1->dau;
                $miz1[] = $mix;
            }
        } else {
            foreach ($miz0 as $mix) {
                $mix['dau1'] = $word1->dau;
                $mix['dau2'] = $word2->dau;
                $miz1[] = $mix;
            }
        }
        if ($word1->phuamdau != $word2->phuamdau) {
            foreach ($miz1 as $mix) {
                $mix['phuamdau1'] = $word1->phuamdau;
                $mix['phuamdau2'] = $word2->phuamdau;
                $miz2[] = $mix;
                $mix['phuamdau1'] = $word2->phuamdau;
                $mix['phuamdau2'] = $word1->phuamdau;
                $miz2[] = $mix;
            }
        } else {
            foreach ($miz1 as $mix) {
                $mix['phuamdau1'] = $word1->phuamdau;
                $mix['phuamdau2'] = $word2->phuamdau;
                $miz2[] = $mix;
            }
        }
        
        if ($word1->phuamcuoi != $word2->phuamcuoi) {
            foreach ($miz2 as $mix) {
                $mix['phuamcuoi1'] = $word1->phuamcuoi;
                $mix['phuamcuoi2'] = $word2->phuamcuoi;
                $mizAll[] = $mix;
                $mix['phuamcuoi1'] = $word2->phuamcuoi;
                $mix['phuamcuoi2'] = $word1->phuamcuoi;
                $mizAll[] = $mix;
            }
        } else {
            foreach ($miz2 as $mix) {
                $mix['phuamcuoi1'] = $word1->phuamcuoi;
                $mix['phuamcuoi2'] = $word2->phuamcuoi;
                $mizAll[] = $mix;
            }
        }
        
        foreach($mizAll as $mix) {
            $w1 = $this->createWord($mix['phuamdau1'], $mix['van1'], $mix['dau1'], $mix['phuamcuoi1']);
            $w2 = $this->createWord($mix['phuamdau2'], $mix['van2'], $mix['dau2'], $mix['phuamcuoi2']);
            if ($this->validate($w1) == null && $this->validate($w2) == null) {
                $words[] = array($w1, $w2);
            }
        }
        
        return $words;
    }
    
    public function addValidator(BaseValidator $validator)
    {
        $this->validators[] = $validator;
    }
    
    public function validate(Word $word)
    {
        foreach($this->validators as $validator) {
            $result = $validator->validate($word);
            if ($result) {
                return $result;
            }
        }
    }
    
    public function createWord($phuamdau, $van, $dau, $phuamcuoi)
    {
        $word = new Word($phuamdau, $van, $dau, $phuamcuoi);
        $word->setFormater($this->formater);
        return $word;
    }
    
    private function mix2Words($word1, $word2)
    {
        $mixes = $this->mixWords($word1, $word2);
        $output = array();
        foreach ($mixes as &$words) {
            $output[] = $words[0] . ' ' . $words[1];
        }
        return $output;
    }
    
    private function mix3Words($word1, $word2, $word3)
    {
        $mixes = $this->mixWords($word1, $word3);
        $output = array();
        foreach ($mixes as &$words) {
            $output[] = $words[0] . ' ' . $word2 . ' ' . $words[1];
        }
        return $output;
    }
    
    private function mix4Words($word1, $word2, $word3, $word4) {
        $lst = $this->mixWords($word2, $word4);
        $output = array();
        foreach($lst as $words) {
            $output[] = $word1 . ' ' . $words[0] . ' ' . $word3 . ' ' . $words[1];
        }
        return $output;
    }
    
    function mixNWords($arr) {
        $i = 0;
        $size = count($arr);
        $segments = array();
        do {
            $segments[] = $this->mix2Words($arr[$i], $arr[$i + 1]);
            $i += 2;
        } while ($i < $size - 3);

        if($i == $size - 2) {
            $segments[] = $this->mix2Words($arr[$i], $arr[$i + 1]);
        } else {
            $segments[] = $this->mix3Words($arr[$i], $arr[$i + 1], $arr[$i + 2]);
        }

        $sSize = count($segments);
        $tmp = $segments[0];
        for ($i = 1; $i < $sSize; $i++) {
            $output = array();
            foreach ($segments[$i] as $part) {
                foreach ($tmp as $tmpPart) {
                    $output[] = $tmpPart . ' ' . $part;
                }
            }
            $tmp = $output;
        }
        return $output;
    }
    
    public function mixPhrase($phrase)
    {
        $words = $this->extractString($phrase);
        $len = count($words);
        if ($len <=1) {
            throw new WordException('Nội dung quá ngắn để nói lái');
        }
        
        if ($len == 2) {
            return $this->mix2Words($words[0], $words[1]);
        }
        
        if ($len == 3) {
            return $this->mix3Words($words[0], $words[1], $words[2]);
        }
        
        if ($len == 4) {
            $newWords = $this->mix4Words($words[0], $words[1], $words[2], $words[3]);
            return array_merge($newWords, $this->mixNWords($words));
        }
        
        if ($len > 4) {
            return $this->mixNWords($words);
        }
    }
}
