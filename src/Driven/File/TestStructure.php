<?php namespace Driven\File;

class TestStructure extends StructureBase
{
    protected $unit;

    public function init()
    {
        $this->unit = new TestDirectory("test", sprintf("%s Unit Tests", $this->namespace));
        $this->unit->addDirectory('fixtures');
        $this->unit
             ->addDirectory($this->namespace)
             ->addDirectory("Domain")
             ->addDirectory("Model");
        $this->unit->directories[1]->addDirectory("Service");
    }

    public function build()
    {
        $this->unit->create();
        $this->unit->directories[1]
                   ->writeFile("TestBase.php", $this->getResource("TestBase.php", array('namespace' => $this->namespace)));
        $this->unit->writeFile("bootstrap.php", $this->getResource("bootstrap.php"));
    }

    public function getRoot()
    {
        return $this->unit;
    }
}