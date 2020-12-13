<?php


namespace Fwk\Util;

class Strings
{
    /**
     * @param $value
     * @return string
     */
    public static function dashToCamelCase($value): string
    {
        return ucfirst(preg_replace_callback('|([-_])([a-z])|', function ($what) {
            if ($what[1] == '_') {
                return '_' . strtoupper($what[2]);
            }

            return strtoupper($what[2]);
        }, strtolower($value)));
    }

    /**
     * @param $value
     * @return string
     */
    public static function camelCaseToDash($value): string
    {
        return strtolower(
            str_replace(
                '\\-',
                '_',
                preg_replace('/([A-Z][a-z])/', '-\1', lcfirst($value))
            )
        );
    }

    /**
     * @param $value
     * @return string
     */
    public static function stringToValidClassName($value): string
    {
        return self::dashToCamelCase(str_replace(
            ['_', ' ', '@', '.'],
            ['_u_', '_', '_at_', '_dot_'],
            $value
        ));
    }

    /**
     * @param $value
     * @return string|string[]
     */
    public static function reverseStringToValidClassName($value)
    {
        $value = str_replace('_u_', '#underscore#', strtolower($value));

        $value = str_replace('#undescore#', '_', $value);

        return $value;
    }
}
