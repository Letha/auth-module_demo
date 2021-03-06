<?php
    // MVC (passive model)
    declare(strict_types=1);
    namespace App;
    use App\Components\Router;
    use App\Components\AppException;
    
    // Stage: General settings.
    ini_set('max_file_uploads', '1');
    ini_set('upload_max_filesize', '307200');
    ini_set('display_errors', '0');
    error_reporting(E_ALL);
    mb_internal_encoding('UTF-8');

    // Stage: Define constants.
    $root = __DIR__;
    if (strlen($root) !== 1) {
        $root .= '/';
    }
    define('ROOT', $root);

    // Stage: Connect system files.
    require_once ROOT . 'autoload.php';

    // Stage: Call Router.
    $router = new Router();
    try {
        $queryState = $router->run();
        if ($queryState === false) {
            header('HTTP/1.1 404 Not Found');
            require ROOT . 'src/views/errors/404.php';
            exit;
        }
    } catch (AppException $exeption) {
        header('HTTP/1.1 500 Internal Server Error');
        require ROOT . 'src/views/errors/500.php';
        exit;
    }
  