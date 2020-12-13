<?php


namespace Fwk\Util;


class Constants
{
    public static function defineMain()
    {
        if (!defined('DATA_DIR')) {
            if (getenv('DATA_DIR')) {
                \define('DATA_DIR', getenv('DATA_DIR'));
            } else {
                \define('DATA_DIR', constant('APP_ROOT') . DIRECTORY_SEPARATOR . 'data');
            }
        }

        if (!defined('APP_ENV') && getenv('APP_ENV')) {
            \define('APP_ENV', strtolower(basename(getenv('APP_ENV'))));
        }
    }

    public static function defineOthers()
    {
        if (!defined('DS')) {
            \define('DS', DIRECTORY_SEPARATOR);
        }

        if (!defined('PS')) {
            \define('PS', PATH_SEPARATOR);
        }

        if (!defined('TEMP_DIR')) {
            \define('TEMP_DIR', constant('DATA_DIR') . DS . 'tmp');
        }

        if (!defined('LOG_DIR')) {
            \define('LOG_DIR', constant('DATA_DIR') . DS . 'logs');
        }

        if (!defined('CACHE_DIR')) {
            \define('CACHE_DIR', constant('DATA_DIR') . DS . 'cache');
        }

        if (!defined('CACHE_SUFFIX')) {
            \define('CACHE_SUFFIX', 'app');
        }

        if (!defined('UPLOAD_DIR')) {
            \define('UPLOAD_DIR', constant('DATA_DIR') . DS . 'upload');
        }

        if (!defined('PUBLIC_DIR')) {
            \define('PUBLIC_DIR', constant('APP_ROOT') . DS . 'public');
        }
    }

    public static function init()
    {
        self::defineMain();
        self::defineOthers();
    }
}