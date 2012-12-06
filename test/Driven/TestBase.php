<?php namespace Driven;

class TestBase extends \PHPUnit_Framework_TestCase
{
    protected function pathToFixture($fixture)
    {
        $fixture = FIXTURES . DS . $fixture;
        if(!file_exists($fixture))
            throw new Exception("Fixture not found");
        
        return $fixture;
    }

    protected function getObjectValue($object, $property)
    {
        $prop = $this->getAccessibleProperty($object, $property);
        return $prop->getValue($object);
    }

    protected function setObjectValue($object, $property, $value)
    {
        $prop = $this->getAccessibleProperty($object, $property);
        $prop->setValue($object, $value);
    }

    private function getAccessibleProperty($object, $property)
    {
        $refl = new \ReflectionObject($object);
        $prop = $refl->getProperty($property);
        $prop->setAccessible(true);
        return $prop;
    }
}