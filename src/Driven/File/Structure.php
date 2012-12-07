<?php namespace Driven\File;

use Driven\Composer\Json,
    Driven\Composer\Autoload,
    Driven\Composer\Requirement,
    Driven\PHPUnit\XmlGenerator,
    webignition\JsonPrettyPrinter\JsonPrettyPrinter;

if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

class Structure
{
    protected $srcDir;
    protected $testDirs = array();
    protected $namespace;
    protected $resource;

    /**
     * @param $projectDir string the root directory
     * @param $namespace string the vendor namespace i.e Driven
     */
    public function __construct($namespace)
    {
        $this->namespace = $namespace;
        $this->srcDir = new SourceDirectory('src');
        $this->resources = dirname(dirname(__DIR__)) . DS . 'resources';
    }

    public function create()
    {
        $this->createSourceDir();
        $this->createTestDirs();
        file_put_contents('composer.json', $this->getComposerJson());
        $this->createPhpUnitXml();
    }

    protected function createSourceDir()
    {
        $this->initSrcDir();
        $this->srcDir->create();
        $this->srcDir->directories[0]
                     ->directories[0]
                     ->directories[0]
                     ->writeFile("Repository.php", $this->getResource('Repository.php', array('namespace' => $this->namespace)));
        $doctrine = $this->srcDir
                         ->directories[0]
                         ->directories[1]
                         ->directories[0]
                         ->directories[0];
        $doctrine->directories[0]->writeFile($this->namespace . '.Domain.Model.Sample.Entity.dcm.xml', $this->getResource('Driven.Domain.Model.Sample.Entity.dcm.xml', array('namespace' => $this->namespace)));
        $doctrine->writeFile("ConfigurationFactory.php", $this->getResource('ConfigurationFactory.php', array('namespace' => $this->namespace)));
        $doctrine->writeFile("EntityManagerFactory.php", $this->getResource('EntityManagerFactory.php', array('namespace' => $this->namespace)));
        $doctrine->writeFile("RepositoryBase.php", $this->getResource('RepositoryBase.php', array('namespace' => $this->namespace)));
        $doctrine->writeFile("UnitOfWork.php", $this->getResource('UnitOfWork.php', array('namespace' => $this->namespace)));
        $doctrine->writeFile("doctrine.cfg.json", $this->getResource('doctrine.cfg.json'));
    }

    protected function createPhpUnitXml()
    {
        $gen = new XmlGenerator();
        foreach($this->testDirs as $dir)
            $gen->addTestDir($dir);
        $gen->saveXML('phpunit.xml.dist');
    }

    protected function getComposerJson()
    {
        $json = new Json(new JsonPrettyPrinter());
        $json->addRequirement(new Requirement("php", ">=5.3.0"));
        $json->addRequirement(new Requirement("phpunit/phpunit", ">=3.7.8"));
        $json->addRequirement(new Requirement("doctrine/orm", "2.3.0"));
        $autoload = new Autoload("psr-0", $this->namespace);
        foreach($this->testDirs as $dir)
            $autoload->addDirectory($dir);
        $autoload->addDirectory($this->srcDir);
        $json->addAutoload($autoload);
        return $json->toJson();
    }

    protected function initSrcDir()
    {
        $main = $this->srcDir->addDirectory($this->namespace);
        $main->addDirectory("Domain")
             ->addDirectory("Model");
        $main->directories[0]->addDirectory("Service");
        $main->addDirectory("Infrastructure")
             ->addDirectory("Persistence")
             ->addDirectory("Doctrine");
        $doctrine = $main->directories[1]
                         ->directories[0]
                         ->directories[0];
        $doctrine->addDirectory("mappings");
        $doctrine->addDirectory("proxies");   
    }

    protected function createTestDirs()
    {
        $unit = $this->initUnitTestStructure();
        $unit->create();
        $unit->directories[1]->writeFile("TestBase.php", $this->getResource("TestBase.php", array('namespace' => $this->namespace)));
        $unit->writeFile("bootstrap.php", $this->getResource("bootstrap.php"));
        $it = $this->initIntegrationTestStructure();
        $it->create();
        $it->directories[0]->writeFile("DbTest.php", $this->getResource("DbTest.php", array('namespace' => $this->namespace)));
        $it->directories[0]
           ->directories[0]
           ->directories[0]
           ->directories[0]->writeFile("DoctrineTest.php", $this->getResource("DoctrineTest.php", array('namespace' => $this->namespace)));
        $functional = new TestDirectory("functional", sprintf("%s Functional Tests", $this->namespace));
        $functional->addDirectory($this->namespace);
        $functional->create();
        $this->testDirs = array_merge($this->testDirs, array($unit, $it, $functional));
    }

    protected function initUnitTestStructure()
    {
        $unit = new TestDirectory("test", sprintf("%s Unit Tests", $this->namespace));
        $unit->addDirectory('fixtures');
        $unit->addDirectory($this->namespace)
             ->addDirectory("Domain")
             ->addDirectory("Model");
        $unit->directories[1]->addDirectory("Service");
        return $unit;
    }

    protected function initIntegrationTestStructure()
    {
        $it = new TestDirectory("it", sprintf("%s Integration Tests", $this->namespace));
        $it->addDirectory($this->namespace)
           ->addDirectory("Infrastructure")
           ->addDirectory("Persistence")
           ->addDirectory("Doctrine");
        $it->directories[0]->addDirectory("datasets");
        return $it;
    }

    protected function getResource($path, $replacements = array())
    {
        $contents = file_get_contents($this->resources . DS . $path);
        foreach($replacements as $key => $value)
            $contents = preg_replace('/{' . $key . '}/', $value, $contents);
        return $contents;
    }
}