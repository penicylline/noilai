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
    require_once __DIR__ . '/src/initialize.php';
    $phrase = filter_input(INPUT_GET, 'q');
    if (!empty($phrase)) {
        try {
            $manager = new WordsManager();
            $newWords = $manager->mixPhrase($phrase);
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