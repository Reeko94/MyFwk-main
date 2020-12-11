<?php


namespace Fwk\Tests\Util;


use Fwk\Util\Constants;
use Fwk\Util\Diagnose;
use PHPUnit\Framework\TestCase;

class DiagnoseTest extends TestCase
{

    protected function setUp(): void
    {
        $GLOBALS['defined'] = [];
        $GLOBALS['getenv'] = [];
        $GLOBALS['define'] = [];
        $GLOBALS['function'] = [];
        $GLOBALS['function_exists'] = [];
        $GLOBALS['extension_loaded'] = [];
    }

    public function testGetFwkVersion()
    {
        $expected = '1.0.0';
        $actual = Diagnose::getFwkVersion(__DIR__ . '/_files/composer.lock');
        $this->assertSame($expected, $actual);
    }

    public function testIsTimeZoneValid()
    {
        $diagnose = new Diagnose(__DIR__ . '/_files/composer.lock');
        $GLOBALS['function']['ini_get']['date.timezone'] = 'Europe/Paris';

        $this->assertTrue($diagnose->isTimezoneValid());
    }

    public function testGetPasswordDefaultAlgo()
    {
        $diagnose = new Diagnose(__DIR__ . '/_files/composer.lock');

        $this->assertIsString($diagnose->getPasswordDefaultAlgo());
    }
    }