<?php
    namespace App\Components;
    use App\Components\Exceptions\DictionaryException;
    /**
     * Class fetches and gives words from dictionarys for view-templates
     * accordingly to language settings. 
     */
    class Dictionary
    {
        private $language;
        private $wordsList;

        public function __construct()
        {
            $langSettingIncludeState = include ROOT . 'src/config/language_settings.php';
            if ($langSettingIncludeState === false) {
                throw new DictionaryException('Language settings was not included.');
            }
            $this->language = $defaultLanguage;
            if (isset($_COOKIE['language']) && in_array($_COOKIE['language'], $existingLanguages)) {
                $this->language = $_COOKIE['language'];
            }
            $wordsList = include ROOT . "src/config/dictionarys/{$this->language}.php";
            if ($wordsList === false) {
                throw new DictionaryException('Word list is not recieved.');
            }
            $this->wordsList = $wordsList;
        }
        public function getWordsList()
        {
            return $this->wordsList;
        }
    }