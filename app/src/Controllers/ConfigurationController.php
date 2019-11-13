<?php
    namespace App\Controllers;
    use App\Core\Controller;
    use App\Components\Session;
    use App\Components\AppException;

    /**
     * Work with program settings.
     * @method public actionLanguage(): void.
     */
    class ConfigurationController extends Controller
    {
        /**
         * Set site language after client request.
         * @return void.
         */
        public function actionLanguage(): void
        {
            // that includes string $defaultLanguage, array $existingLanguages
            $langSettingIncludeState = include ROOT . 'src/config/language_settings.php';
            if ($langSettingIncludeState === false) {
                throw new AppException('ConfigurationController did not recieve language settings.');
            }
            if (isset($_POST['language']) && in_array($_POST['language'], $existingLanguages)) {
                setcookie('language', $_POST['language'], time() + 60*60*24*30);
                session_start();
                $_SESSION['language'] = $_POST['language'];
                session_write_close();

                header('HTTP/1.1 200 OK');
                exit;
            } else {
                header('HTTP/1.1 400 Bad Request');
                exit;
            }
        }
    }