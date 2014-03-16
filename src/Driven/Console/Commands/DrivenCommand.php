<?php namespace Driven\Console\Commands;

use Driven\Doctrine\MigrationInstaller;
use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputArgument,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Formatter\OutputFormatterStyle,
    Driven\File\AppStructure,
    Driven\Composer\Installer;

if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

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
        $output->writeln("<info>Project created.</info>");
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
        $this->installDependencies(getcwd(), $output, $composer);
        $this->installMigrations($output, $composer);
    }

    /**
     * Installs composer dependencies for a given path
     *
     * @param $path
     * @param OutputInterface $output
     * @param $composer
     */
    protected function installDependencies($path, OutputInterface $output, $composer)
    {
        $installer = new Installer($path, $composer);
        $output->writeln('<info>Installing dependencies </info>');
        $installer->run();
        while ($installer->running()) {
            sleep(1);
            $output->write("<info>.</info>");
        }
        $installer->returnToStartingDirectory();
        $output->writeln("\nDependencies installed");
    }

    /**
     * Installs the doctrine migration runner by running php package.php to build
     * an executable phar and then copies it to the project's bin directory
     *
     * @param OutputInterface $output
     * @param $composer
     */
    protected function installMigrations(OutputInterface $output, $composer)
    {
        $path = getcwd() . DS . 'vendor' . DS . 'doctrine' . DS . 'migrations';
        $installer = new MigrationInstaller($path . DS . 'package.php');
        $output->writeln('<info>Installing migration runner</info>');
        $this->installDependencies($path, $output, $composer);
        $installer->run();
        while ($installer->running()) {
            sleep(1);
            $output->write("<info>.</info>");
        }
        $output->writeln("\n<info>Copying migration runner to bin</info>");
        $build = $path . DS . 'build' . DS . 'doctrine-migrations.phar';
        rename($build, getcwd() . DS . 'bin' . DS . 'doctrine-migrations.phar');
        $output->writeln("Migration runner copied");
    }
}