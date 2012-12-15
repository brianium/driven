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
        $this->initClasses();
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

    /**
     * Maintain classes outside of the test. Will look
     * at classes.txt in this directory, and it should have
     * the following format:
     *
     * My\Domain\Model\User,
     * Other\Domain\Objects\Post,
     * Another\Line\With\Model
     */
    protected function initClasses()
    {
        $classList = __DIR__ . DS . 'classes.txt';
        if(file_exists($classList)) {
            $contents = file_get_contents($classList);
            $classes = explode(',', $contents);
            foreach($classes as $class)
                $this->classes[] = trim($class);
        }

    }
}