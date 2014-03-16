<?php
namespace Driven\Doctrine;

use Driven\Process;

class MigrationInstaller extends Process
{
    protected $packagePath;

    public function __construct($packagePath)
    {
        $this->packagePath = $packagePath;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return 'php ' . $this->packagePath;
    }
}