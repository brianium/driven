<?php namespace Driven\File;

class SourceStructure extends StructureBase
{
    protected $src;

    public function init()
    {
        $this->src = new SourceDirectory('src');
        $main = $this->src->addDirectory($this->namespace);
        $this->initDomain($main);
        $this->initInfrastructure($main);
    }

    public function build()
    {
        $this->src->create();
        $this->buildDomain();
        $this->buildInfrastructure();
    }

    public function getRoot()
    {
        return $this->src;
    }

    protected function doctrine()
    {
        return $this->src->directories[0]
                         ->directories[1]
                         ->directories[0]
                         ->directories[0];
    }

    protected function buildDomain()
    {
        $this->src->directories[0]
                  ->directories[0]
                  ->directories[0]
                  ->writeFile("Entity.php", $this->getResource('Entity.php', array('namespace' => $this->namespace)))
                  ->writeFile("Repository.php", $this->getResource('Repository.php', array('namespace' => $this->namespace)));
    }

    protected function buildInfrastructure()
    {
        $doctrine = $this->doctrine();
        $doctrine->directories[0]->writeFile($this->namespace . '.Domain.Model.Sample.Entity.dcm.xml', $this->getResource('Driven.Domain.Model.Sample.Entity.dcm.xml', array('namespace' => $this->namespace)));
        $doctrine->writeFile("ConfigurationFactory.php", $this->getResource('ConfigurationFactory.php', array('namespace' => $this->namespace)));
        $doctrine->writeFile("EntityManagerFactory.php", $this->getResource('EntityManagerFactory.php', array('namespace' => $this->namespace)));
        $doctrine->writeFile("UnitOfWork.php", $this->getResource('UnitOfWork.php', array('namespace' => $this->namespace)));
        $doctrine->writeFile("doctrine.cfg.json", $this->getResource('doctrine.cfg.json'));
        $doctrine->directories[2]->writeFile("RepositoryBase.php", $this->getResource('RepositoryBase.php', array('namespace' => $this->namespace)));
    }

    protected function initDomain($main)
    {
        $main->addDirectory("Domain")
             ->addDirectory("Model");
        $main->directories[0]->addDirectory("Service");
    }

    protected function initInfrastructure($main)
    {
        $main->addDirectory("Infrastructure")
             ->addDirectory("Persistence")
             ->addDirectory("Doctrine");
        $this->doctrine()->addDirectory("mappings");
        $this->doctrine()->addDirectory("proxies");   
        $this->doctrine()->addDirectory('Repositories');
    }

    
    
}