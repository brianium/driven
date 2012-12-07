<?php namespace Driven\Composer;

class Installer
{
    protected static $descriptorspec = array(
       0 => array("pipe", "r"),
       1 => array("pipe", "w"),
       2 => array("pipe", "w")
    );

    protected $binary;
    protected $proc;

    public function __construct($bin = 'composer')
    {
        $this->binary = $bin;
    }

    public function run()
    {
        $cmd = sprintf("%s install", $this->binary);
        $this->proc = proc_open($cmd, static::$descriptorspec, $pipes);
    }

    public function running()
    {
        $status = proc_get_status($this->proc);
        return $status['running'];
    }
}