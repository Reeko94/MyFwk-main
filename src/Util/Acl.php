<?php


namespace Fwk\Util;


class Acl
{
    /**
     * @param $resource
     * @return string[]|null
     */
    public static function getMvcResourceFromString($resource): ?array
    {
        $mvcResource = [
            'type' => 'mvc',
            'module' => '',
            'controller' => '',
            'action' => ''
        ];

        if (substr($resource, 0, 4) !== 'mvc.') {
            return null; // Required
        }

        $mvcParts = explode('.', substr($resource, 4));

        if (!isset($mvcParts[0]) || empty($mvcParts[0]))
            return null; // Module is required
        $mvcResource['module'] = $mvcParts[0];

        if (!isset($mvcParts[1]) || empty($mvcParts[1]))
            return $mvcResource;
        $mvcResource['controller'] = $mvcParts[1];

        if (!isset($mvcParts[2]) || empty($mvcParts[2]))
            return $mvcResource;
        $mvcResource['action'] = $mvcParts[2];

        return $mvcResource;
    }

}