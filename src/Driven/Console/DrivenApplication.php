<?php namespace Driven\Console;

use Symfony\Component\Console\Application,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\InputDefinition,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Output\OutputInterface,
    Driven\Console\Commands\DrivenCommand;

class DrivenApplication extends Application
{
    const NAME = 'Driven';
    const VERSION = '0.1.0';

    public function __construct()
    {
        parent::__construct(static::NAME, static::VERSION);
    }

    public function doRun(InputInterface $input, OutputInterface $output)
    {
        $this->add(new DrivenCommand());
        return parent::doRun($input, $output);
    }

    public function getDefinition()
    {
        return new InputDefinition(array(
            new InputOption('--help',    '-h', InputOption::VALUE_NONE, 'Display this help message.'),
            new InputOption('--verbose', '-v', InputOption::VALUE_NONE, 'Increase verbosity of exceptions.'),
            new InputOption('--version', '-V', InputOption::VALUE_NONE, 'Display this behat version.'),
        ));
    }

    public function getCommandName(InputInterface $input)
    {
        return 'driven';
    }
}