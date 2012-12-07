<?php namespace Driven\File;

use Driven\Composer\Json,
    Driven\Composer\Autoload,
    Driven\Composer\Requirement,
    Driven\PHPUnit\XmlGenerator,
    webignition\JsonPrettyPrinter\JsonPrettyPrinter;

class AppStructure extends StructureBase
{
    protected $source;
    protected $test;
    protected $it;
    protected $functional;
    protected $json;

    public function init()
    {
        $this->source = new SourceStructure($this->namespace);
        $this->unit = new TestStructure($this->namespace);
        $this->it = new IntegrationStructure($this->namespace);
        $this->functional = new FunctionalStructure($this->namespace);
    }

    public function build()
    {
        $this->source->create();
        $this->unit->create();
        $this->it->create();
        $this->functional->create();
        $this->createPhpUnitXml();
        file_put_contents('composer.json', $this->getComposerJson());
    }

    protected function createPhpUnitXml()
    {
        $gen = new XmlGenerator();
        foreach($this->getTestDirs() as $dir)
            $gen->addTestDir($dir);
        $gen->saveXML('phpunit.xml.dist');
    }

    protected function getComposerJson()
    {
        $json = new Json(new JsonPrettyPrinter());
        $this->setJsonRequirements($json);
        $this->setJsonAutoloads($json);
        return $json->toJson();
    }

    protected function setJsonRequirements(Json $json)
    {
        $json->addRequirement(new Requirement("php", ">=5.3.0"));
        $json->addRequirement(new Requirement("phpunit/phpunit", ">=3.7.8"));
        $json->addRequirement(new Requirement("doctrine/orm", "2.3.0"));
        $json->addRequirement(new Requirement("phpunit/dbunit", ">=1.2.1"));
    }

    protected function setJsonAutoloads(Json $json)
    {
        $autoload = new Autoload("psr-0", $this->namespace);
        foreach($this->getTestDirs() as $dir)
            $autoload->addDirectory($dir);
        $autoload->addDirectory($this->source->getRoot());
        $json->addAutoload($autoload);
    }

    protected function getTestDirs()
    {
        return array_map(function($struct) { 
            return $struct->getRoot();
        }, array($this->unit, $this->it, $this->functional));
    }

    public function getRoot() { }
}