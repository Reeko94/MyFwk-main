<?php


namespace Fwk\src\Util;


use Fwk\Util\Debug;
use PHPUnit\Framework\TestCase;

class DebugTest extends TestCase
{

    public function testDumpIsPrintR()
    {
        ob_start();
        Debug::dump(true);
        $result = ob_get_clean();

        $this->assertSame('<pre>1</pre>', $result);
    }

    public function testDumpWithTitle()
    {
        $result = Debug::dump(true, 'bool', true);
        $this->assertSame('<strong>bool</strong><pre>1</pre>', $result);
    }

}