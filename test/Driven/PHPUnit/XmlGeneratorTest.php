<?php namespace Driven\PHPUnit;

use Driven\File\TestDirectory;

class XmlGeneratorTest extends \Driven\TestBase
{
    public function test_saveXML_should_output_correct_xml()
    {
        $gen = new XmlGenerator();
        $cwd = getcwd() . DS;
        $gen->addTestDir(new TestDirectory($cwd . 'test', 'Unit Tests'));        
        $gen->addTestDir(new TestDirectory($cwd . 'it', 'Integration Tests'));
        $gen->addTestDir(new TestDirectory($cwd . 'functional', 'Functional Tests'));
        $file = $cwd . 'phpunit.xml'; 
        $xml = $gen->saveXML($file);
        $this->assertTrue(file_exists($file));
        $this->assertEquals(file_get_contents($this->pathToFixture('phpunit' . DS . 'phpunit.xml')), $xml);
        unlink($file);
    }
}