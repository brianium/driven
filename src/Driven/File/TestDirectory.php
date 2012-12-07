<?php namespace Driven\File;

class TestDirectory extends Directory
{
    public $name;

    public function __construct($path, $name)
    {
        parent::__construct($path);
        $this->name = $name;
    }
}