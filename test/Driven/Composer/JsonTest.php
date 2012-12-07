<?php namespace Driven\Composer;

use webignition\JsonPrettyPrinter\JsonPrettyPrinter,
    Driven\File\Directory;

class JsonTest extends \Driven\TestBase
{
    public function test_toJson_should_return_valid_json()
    {
        $json = new Json(new JsonPrettyPrinter());
        $json->addRequirement(new Requirement("php", ">=5.3.0"));
        $json->addRequirement(new Requirement("phpunit/phpunit", ">=3.7.8"));
        $autoload = new Autoload("psr-0", 'ProjectName');
        $autoload->addDirectory(new Directory("it"))
                 ->addDirectory(new Directory("test"))
                 ->addDirectory(new Directory("functional"));
        $json->addAutoload($autoload);
        $this->assertEquals(
            file_get_contents($this->pathToFixture('json' . DS . 'composer.json'))
            , $json->toJson()
        );
    }
}