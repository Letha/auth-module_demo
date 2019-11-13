<?php
    use App\Components\TextEditor;
    $path = __DIR__;
    $path = substr($path, 0, -strlen('tests/unit/Components'));
    define('ROOT', $path);
    require_once ROOT . 'autoload.php';
    class TextEditorTest extends PHPUnit\Framework\TestCase
    {
        public static function setUpBeforeClass(): void
        {
            mb_internal_encoding('UTF-8');
        }
        public function providerMBUcfirst(): array
        {
            return array(
                ['далеко зашли', 'Далеко зашли'],
                ['если', 'Если'],
                ['walk far', 'Walk far'],
                ['other', 'Other']
            );
        }
        /**
         * @dataProvider providerMBUcfirst
         */
        public function testGetConnection(string $given, string $expected): void
        {
            $this->assertSame($expected, TextEditor::mbUcfirst($given));
        }
    }