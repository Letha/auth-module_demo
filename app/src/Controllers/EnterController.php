<?php
    namespace App\Controllers;
    use App\Core\Controller;
    use App\Models\UsersModel;
    use App\Components\AppException;

    /**
     * Work with the enter to the system. (E.g. login and register.)
     * @method public actionLogin(): void.
     * @method public actionRegister(): void.
     * @method public actionExit(): void.
     */
    class EnterController extends Controller
    {
        public function actionLogin(): void
        {
            $loginStatus = UsersModel::login($_POST);
            if ($loginStatus === null) {
                throw new AppException('EnterController: error when login.');
            } elseif($loginStatus === false) {
                header('HTTP/1.1 400 Bad Request');
                exit;
            } elseif($loginStatus === true) {
                header('HTTP/1.1 200 OK');
                session_start();
                $_SESSION['login'] = $_POST['login'];
                session_write_close();
                exit;
            }
        }
        public function actionRegister(): void
        {
            if (isset($_FILES['personalPhoto']) && $_FILES['personalPhoto']['name'] !== '') {
                $registerStatus = UsersModel::register($_POST, $_FILES);
            } else {
                $registerStatus = UsersModel::register($_POST);
            }
            if ($registerStatus === null) {
                throw new AppException('EnterController did not register new user.');
            } elseif ($registerStatus === false) {
                header('HTTP/1.1 409 Conflict');
                exit;
            } elseif ($registerStatus === true) {
                header('HTTP/1.1 200 OK');
                session_start();
                $_SESSION['login'] = $_POST['login'];
                session_write_close();
                exit;
            }
        }
        public function actionExit(): void
        {
                session_start(['read_and_close' => true]);
                if (isset($_SESSION['login'])) {
                    session_start();
                    unset($_SESSION['login']);
                    session_write_close();
                }
                header('HTTP/1.1 200 OK');
                exit;
        }
    }