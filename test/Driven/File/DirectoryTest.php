<?php namespace Driven\File;

class DirectoryTest extends \Driven\TestBase
{
    public function test_create_should_create_dir_in_current_directory()
    {
        $directory = new Directory("dir");
        $directory->create();
        $dir = getcwd() . DS . 'dir';
        $this->assertTrue(file_exists($dir));
        rmdir($dir);
    }

    public function test_create_should_create_dir_with_nested_structure()
    {
        $directory = new Directory("dir");
        $directory->addDirectory("sub");
        $directory->create();
        $dir = getcwd() . DS . 'dir';
        $sub = $dir . DS . 'sub';
        $this->assertTrue(file_exists($dir));
        $this->assertTrue(file_exists($sub));
        rmdir($sub);
        rmdir($dir);
    }

    public function test_writeFile_should_create_file_in_dir()
    {
        $dir = new Directory("dir");
        $dir->create();
        $path = getcwd() . DS . 'dir';
        $dir->writeFile("test.txt", "hello world!");
        $file = $path . DS . 'test.txt';
        $this->assertTrue(file_exists($file));
        $this->assertEquals('hello world!', file_get_contents($file));
        unlink($file);    
        rmdir('dir');
    }
}