<?php


namespace Fwk\Util;


use RuntimeException;

class Arrays
{
    /**
     * @param $item1
     * @param $item2
     * @return int
     */
    public static function itemCompareCallback($item1, $item2): int
    {
        $pos1 = $item1['position'] ?? 0;
        $pos2 = $item2['position'] ?? 0;

        if ($pos1 < $pos2)
            return -1;

        if ($pos1 === $pos2)
            return 0;

        return 1;
    }

    /**
     * @param array $array1
     * @param array $array2
     * @return array
     */
    public static function mergeConfig(array $array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (!is_string($key))
                return array_merge($array1, $array2);

            if(!array_key_exists($key, $array1)){
                $array1[$key] = $value;
                continue;
            }

            if(is_array($value)) {
                if(!is_array($array1[$key])) {
                    throw new RuntimeException('Unable to merge an array with a non array');
                }

                $array1[$key] = self::mergeConfig($array1[$key], $value);

                continue;
            }

            $array1[$key] = $value;
        }

        return $array1;
    }
}