<?php


namespace Fwk\Util;


use ErrorException;

class ErrorHandler
{
    /**
     * @var bool
     */
    protected static bool $convertNoticeToException;

    /**
     * @param $level
     * @param $message
     * @param $file
     * @param $line
     * @return false
     * @throws ErrorException
     */
    public static function handle($level, $message, $file, $line): bool
    {
        if (!error_reporting())
            return false;

        switch ($level) {
            case E_NOTICE:
            case E_USER_NOTICE:
                if (self::$convertNoticeToException)
                    break;
            case E_DEPRECATED:
            case E_USER_DEPRECATED:
                return false;
        }

        throw new ErrorException($message, 0, $level, $file, $line);
    }

    public static function register(bool $convertNoticeToException = true)
    {
        self::$convertNoticeToException = $convertNoticeToException;

        return set_error_handler([__CLASS__, 'handle']);
    }

}