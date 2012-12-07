<?php namespace Driven\File;

class Directory
{
    public $path;
    public $directories = array();
    protected $created = false;

    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Returns the added directory
     */
    public function addDirectory($path)
    {
        if(is_a($path, 'Directory'))
            $dir = $path;
        else
            $dir = new Directory($this->path . DIRECTORY_SEPARATOR . $path);
        $this->directories[] = $dir;
        return $dir;
    }

    public function create()
    {
        mkdir($this->path);
        foreach($this->directories as $dir)
            $dir->create();
        $this->created = true;
    }

    public function writeFile($name, $contents)
    {
        if(!$this->created) throw new \Exception("Must create directory first.");
        $path = $this->path . DIRECTORY_SEPARATOR . $name;
        file_put_contents($path, $contents);
    }
}