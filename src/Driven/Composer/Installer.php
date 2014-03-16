<?php namespace Driven\Composer;

use Driven\Process;

class Installer extends Process
{
    public function __construct($bin = 'composer')
    {
        parent::__construct($bin);
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return sprintf("%s install", $this->binary);
    }
}