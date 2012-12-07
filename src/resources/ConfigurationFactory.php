<?php namespace {namespace}\Infrastructure\Persistence\Doctrine;

use Doctrine\ORM\Tools\Setup;

class ConfigurationFactory
{
    public function __construct() {
        $this->paths = array(__DIR__ . DIRECTORY_SEPARATOR . 'mappings');
    }

    public function build()
    {
        if(getenv('ENV') == 'development')
            return $this->buildDevConfig();

        return $this->buildProdConfig();
    }

    public function buildDevConfig()
    {
        return Setup::createXMLMetadataConfiguration($this->paths, true);
    }

    public function buildProdConfig()
    {
        $proxies = __DIR__ . DIRECTORY_SEPARATOR . 'proxies';
        $config = Setup::createXMLMetadataConfiguration($this->paths, false, $proxies);
        $config->setProxyNamespace('{namespace}\\Infrastructure\\Persistence\\Doctrine\\Proxies');
        return $config;
    }
}