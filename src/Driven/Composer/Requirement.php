<?php namespace Driven\Composer;

class Requirement
{
    public $name;
    public $condition;

    public function __construct($name, $condition)
    {
        $this->name = $name;
        $this->condition = $condition;
    }
}