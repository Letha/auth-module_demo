<?php
    use App\Components\Db;
    $path = __DIR__;
    $path = substr($path, 0, -strlen('tests/unit/Components'));
    define('ROOT', $path);
    require_once ROOT . 'autoload.php';
    class DbTest extends PHPUnit\Framework\TestCase
    {
        public function testGetConnection(): void
        {
            $this->assertInstanceOf(mysqli::class, Db::getConnection());
        }
    }