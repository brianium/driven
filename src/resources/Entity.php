<?php namespace {namespace}\Domain\Model;

abstract class Entity
{
    protected $id;

    public function getId()
    {
        return $this->id;
    }

    public function isNew()
    {
        return !isset($this->id);
    }
}