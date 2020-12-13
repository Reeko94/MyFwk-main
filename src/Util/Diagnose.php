<?php


namespace Fwk\Util;


use DateTimeZone;
use DomainException;

/**
 * Class Diagnose
 * @package Fwk\Util
 */
class Diagnose
{

    const FWP_PACKAGE_NAME = 'saikurin/fwk-main';

    const MIN_REQUIRED_PHP_VERSION = '7.3.0';

    const MAX_REQUIRED_PHP_VERSION = '8.0.0';
    /**
     * @var array|string[][]
     */
    protected array $required = [
        'cli' => [
            'php', 'openssl', 'pdo', 'mbstring', 'intl', 'tokenizer', 'json', 'ctype', 'xml'
        ],
        'web' => [
            'php', 'openssl', 'pdo', 'mbstring', 'intl', 'tokenizer', 'json', 'ctype', 'xml', 'mod_rewrite'
        ]
    ];

    /**
     * Paths & constants
     *
     * @var array
     */
    protected array $paths = [
        'DATA_DIR' => ['label' => 'Data'],
        'TEMP_DIR' => ['label' => 'Tmp'],
        'LOG_DIR' => ['label' => 'Log'],
        'CACHE_DIR' => ['label' => 'Cache'],
        'UPLOAD_DIR' => ['label' => 'Upload']
    ];
    /**
     * @var array|string[][]
     */
    protected array $strings = [
        'openssl' => [
            'label' => 'OpenSSL Module',
            'error' => 'Install and enable openssl extension'
        ],
        'pdo' => [
            'label' => 'PDO Module',
            'error' => 'Install PDO (and PDO Drivers)'
        ],
        'mbstring' => [
            'label' => 'Mbstring Module',
            'error' => 'Install and enable mbstring extension'
        ],
        'intl' => [
            'label' => 'Intl Module',
            'error' => 'Instal and enable intl extension'
        ],
        'tokenizer' => [
            'label' => 'Tokenizer Module',
            'error' => 'Install and enable tokenizer extension'
        ],
        'json' => [
            'label' => 'JSON Module',
            'error' => 'Install and enable JSON extension'
        ],
        'ctype' => [
            'label' => 'Ctype module',
            'error' => 'Install and enable ctype extension'
        ],
        'xml' => [
            'label' => 'XML Module',
            'error' => 'Install and enable XML extension'
        ],
        'mod_rewrite' => [
            'label' => 'Apache mod_rewrite',
            'error' => 'Install and enable mod_rewrite on your server'
        ],
        'php' => [
            'label' => 'PHP Version',
            'error' => 'PHP version must be at least ' . self::MIN_REQUIRED_PHP_VERSION . ' (' . PHP_VERSION . ' installed). Install PHP ' . self::MIN_REQUIRED_PHP_VERSION . ' or newer (lower than ' . self::MAX_REQUIRED_PHP_VERSION . ')'
        ]
    ];
    /**
     * @var array
     */
    protected array $requirements = [];
    /**
     * @var string
     */
    protected string $currentTimezone;
    /**
     * @var string
     */
    protected string $fwkVersion;

    /**
     * Diagnose constructor.
     * @param string $lockFile
     */
    public function __construct($lockFile = APP_ROOT . DS . 'composer.lock')
    {
        $this->fwkVersion = self::getFwkVersion($lockFile);
    }

    /**
     * @param string $lockFile
     * @return string
     */
    public static function getFwkVersion($lockFile = APP_ROOT . DS . 'composer.lock'): string
    {
        $version = '';


        if (!file_exists($lockFile)) {
            return $version;
        }

        $content = file_get_contents($lockFile);
        $content = json_decode($content, true);

        if ($content !== null && isset($content['packages'])) {
            foreach ($content['packages'] as $package) {
                if ($package['name'] === self::FWP_PACKAGE_NAME) {
                    $version = $package['version'];
                    break;
                }
            }
        }

        return $version;
    }

    /**
     * @param $mode
     * @return array
     */
    public function checkRequirements($mode): array
    {
        $required = $this->getRequired();

        if (!isset($required[$mode])) {
            throw new DomainException("$mode is not available, please use 'cli' or 'web");
        }

        foreach ($required[$mode] as $item) {
            $method = 'has' . $this->toCamelCase($item);
            if (!method_exists($this, $method)) {
                throw new DomainException("Method $method doesn't exists");
            }

            $this->requirements[$item] = $this->$method();
        }
        return $this->requirements;
    }

    /**
     * @return bool
     */
    public function hasModRewrite(): bool
    {
        return \function_exists('apache_get_modules') && in_array('mod_rewrite', apache_get_modules());
    }

    /**
     * @return bool
     */
    public function hasXml(): bool
    {
        return \function_exists('utf8_decode');
    }

    /**
     * @return bool
     */
    public function hasCtype(): bool
    {
        return \function_exists('ctype_alpha');
    }

    /**
     * @return bool
     */
    public function hasJson(): bool
    {
        return \function_exists('json_decode');
    }

    /**
     * @return bool
     */
    public function hasTokenizer(): bool
    {
        return \extension_loaded('tokenizer');
    }

    /**
     * @return bool
     */
    public function hasIntl(): bool
    {
        return \extension_loaded('intl');
    }

    /**
     * @return bool
     */
    public function hasOpenssl(): bool
    {
        return \extension_loaded('openssl');
    }

    /**
     * @return bool
     */
    public function hasPdo(): bool
    {
        return \extension_loaded('pdo') && \extension_loaded('pdo_mysql') && \defined('PDO::ATTR_DRIVeR_NAME');
    }

    public function hasMbstring(): bool
    {
        return \extension_loaded('mbstring');
    }

    /**
     * @return bool
     */
    public function hasPhp(): bool
    {
        return version_compare(PHP_VERSION, self::MIN_REQUIRED_PHP_VERSION, '>=')
            && version_compare(PHP_VERSION, self::MAX_REQUIRED_PHP_VERSION, '<');
    }

    /**
     * @return bool
     */
    public function isTimezoneDefined(): bool
    {
        return boolval(ini_get('date.timezone'));
    }

    /**
     * @return mixed|null
     */
    public function getCurrentTimezone()
    {
        if ($this->isTimezoneDefined())
            return ini_get('date.timezone');

        return null;
    }

    /**
     * @return bool
     */
    public function isTimezoneValid(): bool
    {
        if ($this->isTimezoneDefined() && !is_null($this->getCurrentTimezone())) {
            $timezones = [];
            foreach (DateTimeZone::listAbbreviations() as $listAbbreviations) {
                foreach ($listAbbreviations as $abbreviation) {
                    $timezones[$abbreviation['timezone_id']] = true;
                }
            }

            if (isset($timezones[$this->getCurrentTimezone()]))
                return true;

        }
        return false;
    }

    /**
     * @return bool
     */
    public function isDataDirExists(): bool
    {
        return is_dir(\constant('DATA_DIR'));
    }

    /**
     * @return bool
     */
    public function isDataDirWritable(): bool
    {
        if ($this->isDataDirExists())
            return is_writable(\constant('DATA_DIR'));

        return false;
    }

    /**
     * @return bool
     */
    public function isCacheDirExists(): bool
    {
        return is_dir(constant('CACHE_DIR'));
    }

    /**
     * @return bool
     */
    public function isCacheDirWritable(): bool
    {
        if ($this->isCacheDirExists())
            return is_writable(constant('CACHE_DIR'));

        return false;
    }

    /**
     * @return bool
     */
    public function isLogDirExists(): bool
    {
        return is_dir(constant('LOG_DIR'));
    }

    /**
     * @return bool
     */
    public function isLogDirWritable(): bool
    {
        if ($this->isCacheDirExists())
            return is_writable(constant('LOG_DIR'));

        return false;
    }

    /**
     * @return bool
     */
    public function isTempDirExists(): bool
    {
        return is_dir(constant('TEMP_DIR'));
    }

    /**
     * @return bool
     */
    public function isTempDirWritable(): bool
    {
        if ($this->isCacheDirExists())
            return is_writable(constant('TEMP_DIR'));

        return false;
    }

    /**
     * @return bool
     */
    public function isUploadDirExists(): bool
    {
        return is_dir(constant('UPLOAD_DIR'));
    }

    /**
     * @return bool
     */
    public function isUploadDirWritable(): bool
    {
        if ($this->isCacheDirExists())
            return is_writable(constant('UPLOAD_DIR'));

        return false;
    }

    /**
     * @return array
     */
    public function getRequirements(): array
    {
        return $this->requirements;
    }

    /**
     * @return string
     */
    public function getPhpIniPath(): string
    {
        return \get_cfg_var('cfg_file_path');
    }

    /**
     * @return array|string[][]
     */
    public function getRequired(): array
    {
        if (!\function_exists('apache_get_modules')) {
            $this->required['web'] = array_filter($this->required['web'], function ($row) {
                return $row !== 'mod_rewrite';
            });
        }
        return $this->required;
    }

    /**
     * @return array|string[][]
     */
    public function getStrings()
    {
        return $this->strings;
    }

    /**
     * @return array|string[][]
     */
    public function getPaths(): array
    {
        foreach ($this->paths as $constant => $value) {
            $methodExists = 'is' . ucfirst($this->toCamelCase($constant)) . 'Exists';
            $methodWritable = 'is' . ucfirst($this->toCamelCase($constant)) . 'Writable';

            $this->paths[$constant]['exists'] = $this->$methodExists();
            $this->paths[$constant]['writable'] = $this->$methodWritable();
        }

        return $this->paths;
    }

    /**
     * @return mixed
     */
    public function getPasswordDefaultAlgo()
    {
        $hash = password_hash('12345', PASSWORD_DEFAULT, ['cost' => 5]);
        $infos = password_get_info($hash);

        return $infos['algoName'];
    }

    /**
     * @param $str
     * @return string
     */
    private function toCamelCase($str): string
    {
        $str = preg_replace('/[^a-z0-9]+/i', ' ', $str);
        $str = trim($str);
        $str = strtolower($str);
        $str = ucwords($str);
        $str = str_replace(' ', '', $str);

        return lcfirst($str);
    }
}