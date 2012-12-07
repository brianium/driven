<?php namespace Driven\Console\Commands;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Formatter\OutputFormatterStyle,
    Driven\File\AppStructure,
    Driven\Composer\Installer;

class DrivenCommand extends Command
{
    public function __construct()
    {
        parent::__construct('driven');
    }

    protected function configure()
    {
        $this
            ->addOption('composer', 'c', InputOption::VALUE_REQUIRED, 'The composer binary to execute. <comment>(default: composer)</comment>')
            ->addOption('help', 'h', InputOption::VALUE_NONE, 'Display this help message.')
            ->addArgument('namespace', InputArgument::REQUIRED, 'The namespace for your project');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $style = new OutputFormatterStyle('red', 'yellow', array('bold', 'blink'));
        $output->getFormatter()->setStyle('working', $style);
        $this->createStructure($input, $output);
        $this->install($input, $output);
        $output->write("\n<info>Project created.</info>\n");
        return 1;
    }

    protected function createStructure(InputInterface $input, OutputInterface $output)
    {
        $namespace = $input->getArgument('namespace');
        $structure = new AppStructure($namespace);
        $structure->create();
        $output->writeln('<info>Directory structure created</info>');
    }

    protected function install(InputInterface $input, OutputInterface $output)
    {
        $options = $input->getOptions();
        $composer = isset($options['composer']) ? $options['composer'] : 'composer';
        $installer = new Installer($composer);
        $output->writeln('<info>Installing dependencies </info>');
        $installer->run();
        while($installer->running()) {
            sleep(1);
            $output->write("<info>.</info>");
        }
            
    }
}