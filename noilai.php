<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap-theme.min.css">
        <title>Từ điển nói lái</title>
    </head>
    <body>
<?php

class ChinhTaException extends Exception {
}

global $nguyenam, $nguyenamdoi, $nguyenamba, $nguyenamdonle, $nguyenamtudo;
$nguyenam = ['a', 'ă', 'â', 'e', 'ê', 'i', 'o', 'ô', 'ơ', 'u', 'ư', 'y'];
$nguyenamdoi = ['oo', 'iê', 'ươ', 'oe', 'ai', 'au', 'ua', 'ưa', 'ưu', 'âu', 'uâ', 'ia', 'ui', 'ưi', 'iu', 'êu',
                ' oi', 'ôi', 'ơi', 'ay', 'ây', 'uy', 'uô', 'uâ', 'oa', 'oă', 'ao', 'eo', 'uê'];
$nguyenamba = ['uya', 'ươi', 'uyê', 'iêu', 'oai', 'oay', 'uây', 'uôi', 'ươu'];
$nguyenamdonle = ['uya', 'ươi', 'iêu', 'oai', 'uây', 'uôi', 'ươu', 'eo', 'ao', 'ây','ơi', 'ôi', 
                    'oi', 'êu', 'iu', 'ưi', 'ia', 'âu', 'ưu', 'ưa', 'ua', 'au', 'ai', 'ay'];

$nguyenamtudo = ['oa', 'oe', 'uy', 'uê']; // các nguyên âm có hoặc ko có phụ âm đi sau


global $listphuam;
$listphuam = ['ngh', 'ng', 'nh', 'ch', 'tr', 'ph', 'kh', 'th', 'gh', 'gi', 'qu',
    'b', 'c', 'd', 'đ', 'g', 'h', 'k', 'l', 'm', 'n', 'p', 'r', 's', 't', 'v', 'x'];

global $listphuamcuoi;
$listphuamcuoi = ['ng', 'nh', 'ch', 'c', 'm', 'n', 'p', 't'];

global $listdau;
$listdau = [
    'huyen' => ['à','ằ','ầ','è', 'ề', 'ì', 'ò', 'ồ', 'ờ', 'ù', 'ừ', 'ỳ'],
    'sac' => ['á','ắ','ấ','é', 'ế', 'í', 'ó', 'ố', 'ớ', 'ú', 'ứ', 'ý'],
    'hoi' => ['ả','ẳ','ẩ','ẻ', 'ể', 'ỉ', 'ỏ', 'ổ', 'ở', 'ủ', 'ử', 'ỷ'],
    'nga' => ['ã','ẵ','ẫ','ẽ', 'ễ', 'ĩ', 'õ', 'ỗ', 'ỡ', 'ũ', 'ữ', 'ỹ'],
    'nang' => ['ạ','ặ','ậ','ẹ', 'ệ', 'ị', 'ọ', 'ộ', 'ợ', 'ụ', 'ự', 'ỵ'],
    'ngang' => ['a', 'ă', 'â', 'e', 'ê', 'i', 'o', 'ô', 'ơ', 'u', 'ư', 'y'],
];

function swapSign($word1, $word2) {
    list($dau1, $pos1) = laydau($word1);
    list($dau2, $pos2) = laydau($word2);
    
    $newWord = array(
        doidau($word1, $dau1, $dau2, $pos1),
        doidau($word2, $dau2, $dau1, $pos2)
    );
    
    return $newWord;
}

function doidau($word, $daucu, $daumoi, $pos) {
    global $listdau;
    global $listphuam;
    if ($pos != -1) {
        //detect được vị trí dấu
        $oldChar = $listdau[$daucu][$pos];
        $newChar = $listdau[$daumoi][$pos];
        return str_replace($oldChar, $newChar, $word);
    } else {
        $last = 0;
        foreach($listdau as &$dau) {
            foreach($dau as &$sign) {
                $newpos = mb_strrpos($word, $sign);
                if ($newpos > $last) {
                    $last = $newpos;
                }
            }
        }
        if ($last > 0 && $last < strlen($word) - 1) {
            if (!in_array($word[$last - 1], $listphuam)) {
                $last--;
            }
        }
        $oldChar = $listdau[$daucu][$last];
        $newChar = $listdau[$daumoi][$last];
        return str_replace($oldChar, $newChar, $word);
    }
}

function extractWord($word) {
    global $listphuam;
    global $listphuamcuoi;
    $van = $phuamdau = $phuamcuoi = '';
    foreach($listphuam as $phuam) {
        if (empty($phuamdau) && mb_strpos($word, $phuam, 0, 'UTF-8') === 0) {
            $phuamdau = $phuam;
        }
        if(empty($phuamcuoi) && substr($word, -mb_strlen($phuam, 'UTF-8')) === $phuam) {
            if (!in_array($phuam, $listphuamcuoi)) {
                throw new ChinhTaException($phuam . ' không thể đứng cuối chữ');
            }
            $phuamcuoi = $phuam;
        }
    }
    $wordLen = strlen($word);
    if ($wordLen - strlen($phuamdau) - strlen($phuamcuoi) <= 0) {
        throw new ChinhTaException('Không có vần trong chữ ' . $word);
    }
    $van = substr($word, strlen($phuamdau), $wordLen - mb_strlen($phuamdau) - mb_strlen($phuamcuoi));
    return array($van, $phuamdau, $phuamcuoi);
}

function isValidPhrase($phrase) {
    $words = explode(' ', $phrase);
    foreach ($words as &$word) {
        list($van, $phuamdau, $phuamcuoi) = extractWord($word);
        if (isInvalidWord($van, $phuamdau, $phuamcuoi)) {
            return false;
        }
    }
    return true;
}

function filterPhrases($arr) {
    $arr = array_unique($arr);
    $output = array();
    foreach($arr as &$phrase) {
        $phrase = trim($phrase);
        if (isValidPhrase($phrase)) {
            $output[] = $phrase;
        }
    }
    return $output;
}

function laydau($word) {
    global $listdau;
    foreach ($listdau as $dau => &$arr) {
        foreach ($arr as $i => &$char) {
            if (mb_strpos($word, $char) !== false) {
                return array($dau, $i);
            }
        }
    }
    return array('ngang', -1);
}

function extractWords($phrase) {
    $phrase = preg_replace('!\s+!', ' ', trim($phrase));
    $words = explode(' ', $phrase);
    return $words;
}

function genWords($word1, $word2) {
    list($van1, $phuamdau1, $phuamcuoi1) = extractWord($word1);
    list($van2, $phuamdau2, $phuamcuoi2) = extractWord($word2);
    $err = isInvalidWord($van1, $phuamdau1, $phuamcuoi1);
    if (!empty($err)) {
        throw new ChinhTaException($err);
    }
    $err = isInvalidWord($van2, $phuamdau2, $phuamcuoi2);
    if (!empty($err)) {
        throw new ChinhTaException($err);
    }
    for ($i = 1; $i <= 2; $i++) {
        for ($j = 1; $j <= 2; $j++) {
            for ($k = 1; $k <= 2; $k++) {
                $output[] = swapSign(
                    ${'phuamdau' . $i} . ${'van' . $j} . ${'phuamcuoi' . $k},
                    ${'phuamdau' . ($i % 2 + 1)} . ${'van' . ($j % 2 + 1)} . ${'phuamcuoi' . ($k % 2 + 1)}
                    );
                    
                $output[] = array(
                    ${'phuamdau' . $i} . ${'van' . $j} . ${'phuamcuoi' . $k},
                    ${'phuamdau' . ($i % 2 + 1)} . ${'van' . ($j % 2 + 1)} . ${'phuamcuoi' . ($k % 2 + 1)},
                );
            }
        }
    }
    
    return $output;
}

function gen2Words($word1, $word2, $filter = true) {
    $lst = genWords($word1, $word2);
    $output = array();
    foreach($lst as $words) {
        $output[] = implode(' ', $words);
    }
    if ($filter) {
        return filterPhrases($output);
    }
    return $output;
}

function gen3Words($word1, $word2, $word3, $filter = true) {
    $lst = genWords($word1, $word3);
    $output = array();
    foreach($lst as $words) {
        $output[] = $words[0] . ' ' . $word2 . ' ' . $words[1];
    }
    if ($filter) {
        return filterPhrases($output);
    }
    return $output;
}

function genNWords($arr) {
    $i = 0;
    $size = count($arr);
    $segments = array();
    do {
        $segments[] = gen2Words($arr[$i], $arr[$i + 1], false);
        $i += 2;
    } while ($i < $size - 3);
    
    if($i == $size - 2) {
        $segments[] = gen2Words($arr[$i], $arr[$i + 1], false);
    } else {
        $segments[] = gen3Words($arr[$i], $arr[$i + 1], $arr[$i + 2], false);
    }
    
    $output = array();
    $outputSize = count($segments[0]);
    $phraseSize = count($segments);
    for ($i = 0; $i < $outputSize; $i++) {
        $phrase = '';
        for ($j = 0; $j < $phraseSize; $j++) {
            $phrase .= $segments[$j][$i] . ' ';
        }
        $output[] = $phrase;
    }
    return filterPhrases($output);
}
 
function isInvalidWord($van, $phuamdau, $phuamcuoi) {
    $len = mb_strlen($van, 'UTF-8');
    if ($len > 3) {
        return 'Vần không hợp lệ';
    }
    global $listdau;
    global $nguyenam;
    $count = 0;
    for ($i = 0; $i < $len; $i++) {
        $wordChar = mb_substr($van, $i, 1, 'UTF-8');
        foreach($listdau as $thanh => &$dau) {
            foreach($dau as $pos => &$char) {
                if($char == $wordChar) {
                    if ($thanh == 'ngang') {
                        continue;
                    }
                    $count++;
                    $before = $i == 0 ? '' : mb_substr($van, 0, $i, 'UTF-8');
                    $after = $i == $len - 1 ? '' : mb_substr($van, $i + 1, $len - $i - 1, 'UTF-8');
                    $van = $before . $nguyenam[$pos] . $after;
                    break;
                }
            }
        }
    }
    if($len > 1) {
        global $nguyenamba, $nguyenamdoi;
        if (!in_array($van, $nguyenamba)) {
            if (!in_array($van, $nguyenamdoi)){
                return 'Vần ' . $van . ' không tồn tại';
            }
        }
    }
    if ($count > 1) {
        return 'Qúa nhiều dấu trong vần ' . $van;
    }
    global $nguyenamdonle, $nguyenamtudo;
    if (in_array($van, $nguyenamdonle)) {
        if (strlen($phuamcuoi) > 0) {
            return 'Âm ' . $van . $phuamcuoi . ' không tồn tại';
        }
    } else {
        if (($len > 1 && !in_array($van, $nguyenamtudo) )&& strlen($phuamcuoi) == 0) {
            return 'Vần ' . $van . ' không đứng riêng lẻ';
        }
    }
    return null;
}

$phrase = filter_input(INPUT_GET, 'q');
if (!empty($phrase)) {
    try {
        $words = extractWords(mb_strtolower($phrase, 'UTF-8'));
        if (count($words) == 2) {
            $newWords = gen2Words($words[0], $words[1]);
        }
        if (count($words) == 3) {
            $newWords = gen3Words($words[0], $words[1], $words[2]);
        }
        if (count($words) > 3) {
            $newWords = genNWords($words);
        }
    } catch(ChinhTaException $ex) {
        $error = $ex->getMessage();
    }
        
}
?>
        
        <div class="container" style="margin-top: 100px; width: 940px;">
            <h1 class="text-center text-danger">Từ Điển Nói Lái</h1>
            <form action="?" method="GET" class="form">
                <div class="form-group input-group-lg">
                    <input type="text" name="q" value="<?php echo htmlentities($phrase); ?>" class="form-control" />
                </div>
                <div class="form-group input-group-lg text-center">
                    <input type="submit" value="Là CLGT?" class="btn btn-danger" />
                </div>
            </form>
            <div class="text-center">
                <?php if (isset($newWords)): ?>
                    <h2>Kết Quả</h2>
                    <?php foreach($newWords as $words): ?>
                        <?php echo $words, '<br/>' ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <?php if(isset($error)): ?>
                        <h4 class="text-danger"><?php echo $error ?></h4>
                    <?php else: ?>
                        <?php if (!empty($phrase)): ?>
                            <h4>Á đù, khó quá!</h4>
                        <?php endif; ?>
                    <?php endif;?>
                <?php endif; ?>
            </div>
            
            <footer>
                <p>&copy; <a href="http://datcang.vn" >datcang.vn</a> 2014</p>
            </footer>
        </div>
    </body>
</html>