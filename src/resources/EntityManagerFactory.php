<?php namespace {namespace}\Infrastructure\Persistence\Doctrine;

use {namespace}\Infrastructure\Persistence\Doctrine\ConfigurationFactory,
    Doctrine\ORM\EntityManager;

class EntityManagerFactory
{
    private static $singleton;

    public static function getNewManager()
    {
        $configFactory = new ConfigurationFactory();
        $dbParams = self::getDbParams();
        return EntityManager::create($dbParams, $configFactory->build());
    }

    public static function getDbParams()
    {
        $json = __DIR__ . DIRECTORY_SEPARATOR . 'doctrine.cfg.json';
        $configs = json_decode(file_get_contents($json));

        $paramsKey = (getenv('ENV') == 'development') ? 'development' : 'production';

        if(!property_exists($configs->params, $paramsKey))
            return array();
                   
        return get_object_vars($configs->params->{$paramsKey});
    }

    public static function getSingleton()
    {
        if(is_null(self::$singleton))
            self::$singleton = self::getNewManager();

        return self::$singleton;
    }
}