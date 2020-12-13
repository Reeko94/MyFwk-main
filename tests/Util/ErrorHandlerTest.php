<?php


namespace Fwk\Tests\Util;


use Fwk\Util\ErrorHandler;
use PHPUnit\Framework\TestCase;

class ErrorHandlerTest extends TestCase
{

    protected string $errorReporting;

    protected function setUp(): void
    {
        $this->errorReporting = ini_get('error_reporting');
        ini_set('error_reporting',E_ALL & ~E_NOTICE);
    }

    protected function tearDown(): void
    {
        ini_set('error_reporting', $this->errorReporting);
        restore_error_handler();
    }

    public function testThrowExceptionOnNotice()
    {
        $this->expectException('\\ErrorException');

        ErrorHandler::register(true);
        $tab = [];
        $tab[0]; // Notice : Undefined offset : 0
    }

    public function testThrowExceptionWarning()
    {
        $this->expectException('\\ErrorException');

        ErrorHandler::register(true);
        fopen(); // Warning : fopen() excepts at least 2 parameters, 0 given
    }

    public function testDontThrowExceptionIfSilenced()
    {
        ErrorHandler::register(true);

        @fopen(); // Warninig: fopen() excepts at least 2 parameters, 0 given
        $this->addToAssertionCount(1);
    }
}