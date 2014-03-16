<?php namespace Driven\Composer;

use webignition\JsonPrettyPrinter\JsonPrettyPrinter;

class Json
{
    protected $autoloads = array();
    protected $requirements = array();
    protected $devRequirements = array();
    protected $minimumStability;
    protected $printer;

    public function __construct($printer)
    {
        $this->printer = $printer;
    }

    public function addRequirement(Requirement $req)
    {
        if ($req->isDev) {
            $this->devRequirements[$req->name] = $req->condition;
            return;
        }
        $this->requirements[$req->name] = $req->condition;
    }

    public function addAutoload(Autoload $auto)
    {
        if(!isset($this->autoloads[$auto->type]))
            $this->autoloads[$auto->type] = array();
        $this->autoloads[$auto->type][$auto->vendor] = array_map(function($dir) {
            return basename($dir->path) . DS;
        }, $auto->directories);
    }

    public function setMinimumStability($stability)
    {
        $this->minimumStability = $stability;
    }

    public function toJson()
    {
        $tree = array(
            'autoload' => $this->autoloads,
            'require' => $this->requirements
        );
        if ($this->devRequirements)
            $tree['require-dev'] = $this->devRequirements;
        if ($this->minimumStability)
            $tree['minimum-stability'] = $this->minimumStability;
        $json = json_encode($tree);
        return $this->printer->format($json);
    }
}