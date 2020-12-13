<?php


namespace Fwk\Util;


use RuntimeException;

class Passwords
{

    public static function checkPassword(array $userProvided, array $expected): bool
    {
        if (empty($expected['password']) || empty($userProvided['password']))
            throw new RuntimeException('Password cannot be an empty value');

        return password_verify($userProvided['password'], $expected['password']);
    }

    /**
     * @param $password
     * @return string
     */
    public static function getHashFromPassword($password): string
    {
        if (empty($password))
            throw new RuntimeException('The password must be provided');

        $hash = password_hash($password, PASSWORD_DEFAULT);

        if (!$hash)
            throw new RuntimeException('The password hashing failed');

        return $hash;
    }

    /**
     * @param $hash
     * @param $password
     * @return false|string|null
     */
    public static function passwordNeedsRehash($hash, $password)
    {
        $needRehash = false;

        // 1st case: hash is a MD5 sum
        if (strlen($hash) === 32)
            $needRehash = true;

        // 2nd case: The algorithm has evolved
        if (password_needs_rehash($hash, PASSWORD_DEFAULT)) {
            $needRehash = true;
        }
        // If the hash has expired, the replacement value is returned.
        if($password != '' && $needRehash)
            return password_hash($password, PASSWORD_DEFAULT);

        return false;
    }

}