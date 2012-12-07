<?php namespace Driven\File;

class IntegrationStructure extends StructureBase
{
    protected $it;

    public function init()
    {
        $this->it = new TestDirectory("it", sprintf("%s Integration Tests", $this->namespace));
        $this->it
           ->addDirectory($this->namespace)
           ->addDirectory("Infrastructure")
           ->addDirectory("Persistence")
           ->addDirectory("Doctrine");
        $this->it->directories[0]->addDirectory("datasets");
    }

    public function build()
    {
        $this->it->create();
        $this->it->directories[0]
                 ->writeFile("DbTest.php", $this->getResource("DbTest.php", array('namespace' => $this->namespace)));
        $this->it->directories[0]
           ->directories[0]
           ->directories[0]
           ->directories[0]
           ->writeFile("DoctrineTest.php", $this->getResource("DoctrineTest.php", array('namespace' => $this->namespace)));
    }

    public function getRoot()
    {
        return $this->it;
    }
}