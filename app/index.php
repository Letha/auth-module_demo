<?php
    declare(strict_types=1);
    namespace App;
    use App\Controllers\FrontController;
    use App\Components\Router;
    use App\Components\AppException;
    use App\Components\Exceptions;

    // Stage: General settings.
    ini_set('display_errors', '1');
    error_reporting(E_ALL);
    mb_internal_encoding("UTF-8");

    // Stage: Define constants.
    $root = __DIR__;
    if (strlen($root) !== 1) {
        $root .= '/';
    }
    define('ROOT', $root);

    // Stage: Connect system files.
    require_once ROOT . 'autoload.php';

    // Stage: Connect db.

    // Stage: Call Router.
    $router = new Router();
    try {
        $queryState = $router->run();
        if ($queryState === false) {
            header('HTTP/1.1 404 Bad Request');
            require ROOT . 'src/Views/Errors/404.php';
            exit;
        }
    } catch (Exceptions\RouterException $exeption) {
        header('HTTP/1.1 500 Internal Server Error');
        require ROOT . 'src/Views/Errors/500.php';
        exit;
    }
?>
  