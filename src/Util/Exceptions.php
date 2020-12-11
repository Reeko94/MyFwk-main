<?php


namespace Fwk\Util;


use Exception;

class Exceptions
{

    public static function formatException(Exception $e, $preMessage = null, $newLine = "\r\n")
    {
        $currentException = $e;
        while (true) {
            if ($currentException === $e) {
                $preMessage .= self::formatAsString($currentException, $newLine);
            } else {
                $preMessage .= $newLine . 'Cause by:' . self::formatAsString($currentException, $newLine);
            }

            $currentException = $currentException->getPrevious();
            if (!isset($currentException))
                break;
        }
        return $preMessage;
    }

    protected static function formatAsString(Exception $e, $newLine = "\r\n"): string
    {
        return get_class($e) . ': ' .
            $e->getMessage() . $newLine .
            '#>' . $e->getFile() . '(' . $e->getLine() . ')' . $newLine .
            str_replace("\n", $newLine, $e->getTraceAsString());
    }

}