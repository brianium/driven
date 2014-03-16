<?php namespace Driven\Composer;

class Requirement
{
    public $name;
    public $condition;
    public $isDev;

    public function __construct($name, $condition, $isDev = false)
    {
        $this->name = $name;
        $this->condition = $condition;
        $this->isDev = $isDev;
    }
}