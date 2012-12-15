<?php namespace Driven\File;

class AppStructureTest extends \Driven\TestBase
{
    protected $structure;

    public function setUp()
    {
        if(file_exists('driventest'))
            system('/bin/rm -rf ' . escapeshellarg('driventest'));
        mkdir('driventest');
        chdir('driventest');
        $this->structure = new AppStructure('Driven');
        $this->structure->create();
    }

    public function test_create_should_create_complete_source_directory()
    {
        $this->assertTrue(file_exists('bin/doctrine'));
        $this->assertTrue(file_exists('composer.json'));
        $this->assertTrue(file_exists('phpunit.xml.dist'));
        $this->assertTrue(file_exists('src/Driven/Domain/Model/Entity.php'));
        $this->assertTrue(file_exists('src/Driven/Domain/Model/Repository.php'));
        $this->assertTrue(file_exists('src/Driven/Domain/Service'));
        $this->assertTrue(file_exists('src/Driven/Infrastructure/Persistence/Doctrine/mappings'));
        $this->assertTrue(file_exists('src/Driven/Infrastructure/Persistence/Doctrine/proxies'));
        $this->assertTrue(file_exists('src/Driven/Infrastructure/Persistence/Doctrine/ConfigurationFactory.php'));
        $this->assertTrue(file_exists('src/Driven/Infrastructure/Persistence/Doctrine/doctrine.cfg.json'));
        $this->assertTrue(file_exists('src/Driven/Infrastructure/Persistence/Doctrine/EntityManagerFactory.php'));
        $this->assertTrue(file_exists('src/Driven/Infrastructure/Persistence/Doctrine/Repositories/RepositoryBase.php'));
        $this->assertTrue(file_exists('src/Driven/Infrastructure/Persistence/Doctrine/UnitOfWork.php'));
    }

    public function test_create_should_make_test_dirs()
    {
        $this->assertTrue(file_exists('test/bootstrap.php'));
        $this->assertTrue(file_exists('test/fixtures')); 
        $this->assertTrue(file_exists('test/Driven/TestBase.php'));
        $this->assertTrue(file_exists('it/Driven/DbTest.php'));
        $this->assertTrue(file_exists('it/Driven/Infrastructure/Persistence/Doctrine/Repositories'));  
        $this->assertTrue(file_exists('it/Driven/Infrastructure/Persistence/Doctrine/DoctrineTest.php'));  
        $this->assertTrue(file_exists('functional/Driven'));
    }

}