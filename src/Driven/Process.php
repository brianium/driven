<?php
namespace Driven;

abstract class Process
{
    public static $descriptorspec = array(
        0 => array("pipe", "r"),
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );

    protected $binary;
    protected $proc;

    public function __construct($bin)
    {
        $this->binary = $bin;
    }

    public function run()
    {
        $cmd = $this->getCommand();
        $this->proc = proc_open($cmd, static::$descriptorspec, $pipes);
    }

    public function running()
    {
        $status = proc_get_status($this->proc);
        return $status['running'];
    }

    /**
     * @return string
     */
    public abstract function getCommand();
} 