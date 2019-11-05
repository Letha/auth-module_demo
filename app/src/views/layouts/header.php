<?php
    use App\Components\Dictionary;
    use App\Components\Exceptions;
    try {
        $dict = new Dictionary();
        $words = $dict->getWordsList();
    } catch (Exceptions\DictionaryException $exeption) {
        header('HTTP/1.1 500 Internal Server Error');
        require ROOT . 'src/Views/Errors/500.php';
        exit;
    }
?>
<!doctype html>
<html>
<head>
    <meta charset='utf-8'>
    <meta http-equiv='X-UA-Compatible' content='IE=Edge'>
    <meta name='viewport' content='width=device-width, initial-scale=1, shrink-to-fit=no'>

    <title><?=isset($htmlPreprocessor) ? $htmlPreprocessor->title : 'App'?></title>

    <!-- own styles - begin -->
    <link rel='stylesheet' href='./front-end/main.css'>
    <!-- end -->
</head>
<body>