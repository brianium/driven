<?php namespace Driven\PHPUnit;

use Driven\File\TestDirectory;

class XmlGenerator
{
    protected $document;
    protected $root;
    protected $suites = array();

    public $attributes = array(
        'backupGlobals' => 'false',
        'backupStaticAttributes' => 'false',
        'bootstrap' => './test/bootstrap.php',
        'colors' => 'true',
        'convertErrorsToExceptions' => 'true',
        'convertNoticesToExceptions' => 'true',
        'convertWarningsToExceptions' => 'true',
        'processIsolations' => 'false',
        'stopOnFailures' => 'false',
        'syntaxCheck' => 'false'
    );

    public function __construct()
    {
        $this->document = new \DOMDocument("1.0", "UTF-8");
        $this->root = $this->document->createElement("phpunit");
    }

    public function addTestDir(TestDirectory $dir)
    {
        $this->suites[] = array(
            'name' => $dir->name, 
            'directory' => "." . DIRECTORY_SEPARATOR . basename($dir->path) . DIRECTORY_SEPARATOR
        );
        return $this;
    }

    public function saveXML($file)
    {
        foreach($this->attributes as $attr => $value)
            $this->root->setAttribute($attr, $value);
        $suites = $this->getSuitesNode();
        $this->root->appendChild($suites);
        $this->document->appendChild($this->root);
        $this->document->formatOutput = true;
        $xml = $this->document->saveXML();
        file_put_contents($file, $xml);
        return $xml;
    }

    protected function getSuitesNode()
    {
        $node = $this->document->createElement("testsuites");
        foreach($this->suites as $suite) {
            $elem = $this->document->createElement("testsuite");
            $elem->setAttribute('name', $suite['name']);
            $dir = $this->document->createElement("directory", $suite['directory']);
            $elem->appendChild($dir);
            $node->appendChild($elem);
        }
        return $node;
    }
}