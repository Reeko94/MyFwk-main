<?php


namespace Fwk\Tests\Util;


use Fwk\Util\Passwords;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PasswordTest extends TestCase
{
    public function testGetHashFromPasswordThrowExceptionBecauseOfEmptyPassword()
    {
        $this->expectException('RuntimeException');

        $password = null;

        Passwords::getHashFromPassword($password);
    }

    public function testGetHashFromPassword()
    {
        $password = 'test';

        $hash = Passwords::getHashFromPassword($password);

        $this->assertTrue(password_verify($password, $hash));
    }

    public function testCheckPasswordThrowExceptionBecauseOfEmptyPassword()
    {
        $this->expectException('RuntimeException');

        $userProvided = ['password' => null];
        $excepted = ['password' => null];

        Passwords::checkPassword($userProvided, $excepted);
    }

    public function testCheckPasswordWithStandardMethod()
    {
        $password = 'test';

        $expectedPassword = Passwords::getHashFromPassword($password);

        $userProvided = ['password' => $password];
        $expected = ['password' => $expectedPassword];

        $this->assertTrue(Passwords::checkPassword($userProvided, $expected));
    }
}