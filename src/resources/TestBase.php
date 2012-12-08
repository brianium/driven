<?php namespace {namespace};

class TestBase extends \PHPUnit_Framework_TestCase
{
    public function pathToFixture($fixture)
    {
        $fixtures = dirname(__DIR__) . DS . 'fixtures';
        $fixture = $fixtures . DS . $fixture;
        if(!file_exists($fixture))
            throw new Exception("Fixture not found");
        
        return $fixture;
    }

    public function getObjectValue($object, $property)
    {
        $prop = $this->getAccessibleProperty($object, $property);
        return $prop->getValue($object);
    }

    public function setObjectValue($object, $property, $value)
    {
        $prop = $this->getAccessibleProperty($object, $property);
        $prop->setValue($object, $value);
    }

    public function getAccessibleProperty($object, $property)
    {
        $refl = new \ReflectionObject($object);
        $prop = $refl->getProperty($property);
        $prop->setAccessible(true);
        return $prop;
    }
}