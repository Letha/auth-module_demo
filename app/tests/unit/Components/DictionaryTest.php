<?php
    use App\Components\Dictionary;
    $path = __DIR__;
    $path = substr($path, 0, -strlen('tests/unit/Components'));
    define('ROOT', $path);
    require_once ROOT . 'autoload.php';
    class DictionaryTest extends PHPUnit\Framework\TestCase
    {
        private $dictionary;
        private $language;
        private $wordsList;

        public function setUp(): void
        {
            require ROOT . 'src/config/language_settings.php';
            $this->language = $defaultLanguage;
            $this->wordsList = require ROOT . "src/config/dictionarys/{$this->language}.php";
            $this->dictionary = new Dictionary();
        }
        public function testGetLangAndWords(string  $givenLang = 'en', string $mode = 'default'): void
        {
            $this->assertSame($this->language, $this->dictionary->getLanguage());
            $this->assertSame($this->wordsList, $this->dictionary->getWordsList());
        }
    }