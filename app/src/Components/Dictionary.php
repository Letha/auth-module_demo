<?php
    namespace App\Components;
    use App\Components\AppException;
    /**
     * Class fetches and gives words from dictionarys for view-templates
     * accordingly to language settings.
     * @property private $language (string or undefined) - current language.
     * @property private $wordsList (array or undefined) - current dictionary.
     * @method public getWordsList(): array.
     * @method public getLanguage(): string.
     */
    class Dictionary
    {
        private $language;
        private $wordsList;

        public function __construct()
        {
            // that includes string $defaultLanguage, array $existingLanguages
            $langSettingIncludeState = include ROOT . 'src/config/language_settings.php';
            if ($langSettingIncludeState === false) {
                throw new AppException('Dictionary did not recieve language settings.');
            }
            
            $this->language = $defaultLanguage;
            session_start(['read_and_close'  => true]);
            if (isset($_SESSION['language']) && in_array($_SESSION['language'], $existingLanguages)) {
                $this->language = $_SESSION['language'];
            } elseif (isset($_COOKIE['language']) && in_array($_COOKIE['language'], $existingLanguages)) {
                $this->language = $_COOKIE['language'];
            }

            $wordsList = include ROOT . "src/config/dictionarys/{$this->language}.php";
            if ($wordsList === false) {
                throw new AppException('Dictionary did not recieve the word list.');
            }
            $this->wordsList = $wordsList;
        }
        public function getWordsList(): array
        {
            return $this->wordsList;
        }
        public function getLanguage(): string
        {
            return $this->language;
        }
    }