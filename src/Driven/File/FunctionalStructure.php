<?php namespace Driven\File;

class FunctionalStructure extends StructureBase
{
    protected $functional;

    public function init()
    {
        $this->functional = new TestDirectory("functional", sprintf("%s Functional Tests", $this->namespace));
        $this->functional->addDirectory($this->namespace);
    }

    public function build()
    {
        $this->functional->create();
    }

    public function getRoot()
    {
        return $this->functional;
    }
}