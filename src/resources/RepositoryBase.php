<?php namespace {namespace}\Infrastructure\Persistence\Doctrine\Repositories;

use {namespace}\Domain\Model\Repository,
    {namespace}\Infrastructure\Persistence\Doctrine\EntityManagerFactory,
    Doctrine\ORM\EntityManager;

abstract class RepositoryBase implements Repository
{
    protected $manager;
    protected $type;

    public function __construct(EntityManager $em = null)
    {
        if(!class_exists($this->type))
            throw new \DomainException('protected property $type must specify fully qualified Entity class name');

        if(is_null($em))
            $em = EntityManagerFactory::getSingleton();

        if(!is_a($em, 'Doctrine\\ORM\\EntityManager'))
            throw new \InvalidArgumentException('Repository must be constructed with an instance of Doctrine\\ORM\\EntityManager');

        $this->manager = $em;
    }

    public function get($id)
    {
        return $this->manager->find($this->type, $id);
    }

    public function getAll()
    {
        return $this->manager->getRepository($this->type)
                    ->findAll();
    }

    public function getBy($conditions)
    {
        return $this->manager->getRepository($this->type)
                             ->findBy($conditions);
    }

    public function store($entity)
    {
        $this->verifyType($entity);
        $this->manager->persist($entity);
    }

    public function delete($id)
    {
        $entity = $this->get($id);
        $this->manager->remove($entity);
    }

    private function verifyType($entity)
    {
        if(!is_a($entity, $this->type))
            throw new \DomainException("$entity is not an instance of {$this->type}");
    }
}