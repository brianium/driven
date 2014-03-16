<?php namespace Driven\Composer;

use webignition\JsonPrettyPrinter\JsonPrettyPrinter,
    Driven\File\Directory;

class JsonTest extends \Driven\TestBase
{
    protected $json;

    public function setUp()
    {
        $json = new Json(new JsonPrettyPrinter());
        $json->addRequirement(new Requirement("php", ">=5.3.0"));
        $autoload = new Autoload("psr-0", 'ProjectName');
        $autoload->addDirectory(new Directory("it"))
            ->addDirectory(new Directory("test"))
            ->addDirectory(new Directory("functional"));
        $json->addAutoload($autoload);
        $this->json = $json;
    }

    public function test_toJson_should_return_valid_json_when_stability_present()
    {
        $this->json->setMinimumStability('dev');
        $this->json->addRequirement(new Requirement("phpunit/phpunit", ">=3.7.8", true));
        $this->assertEquals(
            file_get_contents($this->pathToFixture('json' . DS . 'composer.json'))
            , $this->json->toJson()
        );
    }

    public function test_toJson_should_return_valid_json_without_stability()
    {
        $this->json->addRequirement(new Requirement("phpunit/phpunit", ">=3.7.8", true));
        $this->assertEquals(
            file_get_contents($this->pathToFixture('json' . DS . 'composer2.json'))
            , $this->json->toJson()
        );
    }

    public function test_toJson_should_return_valid_json_without_dev_requirements()
    {
        $this->assertEquals(
            file_get_contents($this->pathToFixture('json' . DS . 'composer3.json'))
            , $this->json->toJson()
        );
    }
}