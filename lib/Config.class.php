<?php

class Config
{
    private static $configCache = [];

    public static function get($parameter)
    {
        if (!isset(self::getCurrentConfiguration()[$parameter])) {
            throw new Exception('Parameter ' . $parameter . ' does not exists');
        }
        return self::getCurrentConfiguration()[$parameter];
    }

    private static function getCurrentConfiguration()
    {
        if (empty(self::$configCache)) {
            $configDir = __DIR__ . '/../configuration/';
            $configProd = $configDir . 'config.prod.php';
            $configDev = $configDir . 'config.dev.php';
            $configDefault = $configDir . 'config.default.php';
            if (is_file($configProd)) {
                require_once $configProd;
            } else if (is_file($configDev)) {
                require_once $configDev;
            } else if (is_file($configDefault)) {
                require_once $configDefault;
            } else {
                throw new Exception('Unable to find configuration file');
            }
            if (!isset($config) || !is_array($config)) {
                throw new Exception('Unable to load configuration');
            }
            self::$configCache = $config;
        }
        return self::$configCache;
    }
}
?>