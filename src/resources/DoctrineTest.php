<?php namespace {namespace}\Infrastructure\Persistence\Doctrine;

use {namespace}\Infrastructure\Persistence\Doctrine\EntityManagerFactory,
    Doctrine\ORM\Tools\SchemaTool;

abstract class DoctrineTest extends \{namespace}\DbTest
{
    protected $unitOfWork;
    protected $tool;
    protected $classes = array();

    public function __construct($name = null, $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $manager = EntityManagerFactory::getNewManager();
        $this->tool = new SchemaTool($manager);
        $this->classes = array_map(function($entity) use($manager) {
            return $manager->getClassMetadata($entity);
        }, $this->classes);
    }

    public function setUp()
    {
        $this->dropSchema();
        $this->createSchema();
        parent::setUp();
        $this->unitOfWork = new UnitOfWork();
        $this->unitOfWork->begin();
    }

    public function tearDown()
    {
        $this->unitOfWork->commit();
        parent::tearDown();
    }

    public function createSchema()
    {
        $this->tool->createSchema($this->classes);
    }

    public function dropSchema()
    {
        $this->tool->dropSchema($this->classes);
    }
}