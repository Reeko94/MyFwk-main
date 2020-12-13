<?php


namespace Fwk\Tests\Util;

use Fwk\Util\Strings;
use PHPUnit\Framework\TestCase;

class StringsTest extends TestCase
{
    public function testDashToCamelCase()
    {
        $result = Strings::dashToCamelCase('saiku-fwk_test');

        $this->assertSame('SaikuFwk_Test', $result);
    }

    public function testCamelCaseToDash()
    {
        $result = Strings::camelCaseToDash('SaikuFwk\\Test');

        $this->assertSame('saiku-fwk_test', $result);
    }

    public function testStringToValidClassName()
    {
        $resultName = Strings::stringToValidClassName('Some Body');
        $resultMail = Strings::stringToValidClassName('some.bo_dy@mail.com');

        $this->assertSame('Some_Body', $resultName);
        $this->assertSame('Some_Dot_Bo_U_Dy_At_Mail_Dot_Com', $resultMail);
    }
}
