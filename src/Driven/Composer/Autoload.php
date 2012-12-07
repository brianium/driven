<?php namespace Driven\Composer;

use Driven\File\Directory;

class Autoload
{
    public $directories = array();
    public $type;
    public $vendor;

    public function __construct($type, $vendor)
    {
        $this->type = $type;
        $this->vendor = $vendor;
    }

    public function addDirectory($directory)
    {
        $this->directories[] = $directory;
        return $this;
    }
}