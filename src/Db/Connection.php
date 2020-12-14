<?php


namespace Fwk\Db;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection as DbalConnection;
use Doctrine\DBAL\Driver;

class Connection extends DbalConnection
{

    protected array $typeMapping = [
        'enum' => 'string',
        'point' => 'string'
    ];

    public function __construct(array $params, Driver $driver, ?Configuration $config = null, ?EventManager $eventManager = null)
    {
        // Set user's types mapping and reset this array key
        if (isset($params['typeMapping'])) {
            $this->typeMapping = $params['typeMapping'];
            unset($params['typeMapping']);
        }

        parent::__construct($params, $driver, $config, $eventManager);

        // Map the specified types, if it does not already exists
        $platform = $this->getDatabasePlatform();
        foreach ($this->typeMapping as $type => $class) {
            if (!$platform->hasDoctrineTypeMappingFor($type)) {
                $platform->registerDoctrineTypeMapping($type, $class);
            }
        }
    }

}
