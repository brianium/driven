<?php namespace Driven\File;

if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

abstract class StructureBase
{
    protected $namespace;

    public function __construct($namespace)
    {
        $this->namespace = $namespace;
    }

    public function create()
    {
        $this->init();
        $this->build();
    }

    protected function getResource($path, $replacements = array())
    {
        $resources = dirname(dirname(__DIR__)) . DS . 'resources';
        $contents = file_get_contents($resources . DS . $path);
        foreach($replacements as $key => $value)
            $contents = preg_replace('/{' . $key . '}/', $value, $contents);
        return $contents;
    }

    abstract public function init();
    abstract public function build();

    /**
     * should return an instance of Directory
     */
    abstract public function getRoot();
}