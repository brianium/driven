<?php namespace {namespace}\Domain\Model;

interface Repository
{
    public function get($id);
    public function getAll();
    public function getBy($conditions);
    public function store($entity);
    public function delete($id);
}