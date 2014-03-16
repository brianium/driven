<?php namespace Driven\Composer;

use Driven\Process;

class Installer extends Process
{
    protected $location;
    protected $startDir;

    public function __construct($location, $bin = 'composer')
    {
        parent::__construct($bin);
        $this->location = $location;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        $this->startDir = getcwd();
        chdir($this->location);
        return sprintf("%s install", $this->binary);
    }

    public function returnToStartingDirectory()
    {
        chdir($this->startDir);
    }
}