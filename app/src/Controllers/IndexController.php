<?php
    namespace App\Controllers;
    use App\Core\Controller;
    use App\Models\UsersModel;
    use App\Components\AppException;
    /**
     * Work with index page.
     * @method public actionIndex(): void.
     */
    class IndexController extends Controller
    {
        /**
         * Send index template.
         * @return void.
         */
        public function actionIndex(): void
        {
            session_start(['read_and_close' => true]);
            if (isset($_SESSION['login'])) {
                $userData = UsersModel::getCurrentUserData();
                $includeTemplateState = include ROOT . 'src/views/index/authorized.php';
                if ($includeTemplateState === false) {
                    throw new AppException('IndexController did not include template in actionIndex.');
                }
            } else {
                $includeTemplateState = include ROOT . 'src/views/index/unauthorized.php';
                if ($includeTemplateState === false) {
                    throw new AppException('IndexController did not include template in actionIndex.');
                }
            }
        }
    }