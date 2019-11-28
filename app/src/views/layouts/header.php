<?php
    use App\Components\Dictionary;
    use App\Components\Exceptions;
    try {
        $dict = new Dictionary();
        $lang = $dict->getLanguage();
        $words = $dict->getWordsList();
    } catch (Exceptions\DictionaryException $exeption) {
        header('HTTP/1.1 500 Internal Server Error');
        require ROOT . 'src/Views/Errors/500.php';
        exit;
    }
?>
<!doctype html>
<html lang='<?=$lang?>'>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=Edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>

    <title><?=isset($htmlPreprocessor) ? $htmlPreprocessor->title : 'App'?></title>

    <!-- own styles - begin -->
    <link rel='stylesheet' href='/front-end/main.css'>
    <!-- end -->
</head>
<body>
    <div class='b-sidebar b-sidebar_palette_purple'>
        <span><?=$words['language']?>:</span>
        <form name='langToEn' enctype='multipart/form-data'>
            <input type='hidden' name='language' value='en'>
            <button class='b-button b-button_form_1 b-button_palette_white <?=$lang === 'en' ? 'b-button_choosen' : ''?>'
                type='submit' <?=$lang === 'en' ? 'disabled' : ''?> disabled>en</button>
        </form>
        <form name='langToRu' enctype='multipart/form-data'>
            <input type='hidden' name='language' value='ru'>
            <button class='b-button b-button_form_1 b-button_palette_white <?=$lang === 'ru' ? 'b-button_choosen' : ''?>'
                type='submit' <?=$lang === 'ru' ? 'disabled' : ''?> disabled>ru</button>
        </form>
    </div>