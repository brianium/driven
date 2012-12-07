<?php namespace {namespace};

abstract class DbTest extends \PHPUnit_Extensions_Database_TestCase
{
    protected $base;
    protected $schema = ":dbtest:";
    static private $pdo = null;
    private $conn = null;
    protected $dsn = "pgsql:host=%s;dbname=%s;user=%s;password=%s";

    public function __construct($name = null, $data = array(), $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->base = new TestBase();
    }

    final public function getConnection()
    {
        if($this->conn === null) {
            if(self::$pdo == null) {
                $params = $this->getConnectionParams();
                $dsn = sprintf($this->dsn, $params->host, $params->dbname, $params->user, $params->password);
                self::$pdo = new \PDO($dsn);
            }
            $this->conn = $this->createDefaultDBConnection(self::$pdo, $this->schema);
        }
        return $this->conn;
    }

    public function dataset($name)
    {
        return $this->createXMLDataSet(__DIR__ . DS . 'datasets' . DS . $name);
    }

    public function pathToFixture($fixture)
    {
        return $this->base->pathToFixture($fixture);
    }

    public function getObjectValue($object, $property)
    {
        return $this->base->getObjectValue($object, $property);
    }

    public function setObjectValue($object, $property, $value)
    {
        return $this->base->setObjectValue($object, $property, $value);
    }

    public function getAccessibleProperty($object, $property)
    {
        return $this->base->getAccessibleProperty($object, $property);
    }

    protected function getConnectionParams()
    {
        $json  = dirname(dirname(__DIR__)) . DS . 'src' . DS . 'Consumed' . DS . 'Infrastructure' . DS;
        $json .= 'Persistence' . DS . 'Doctrine' . DS . 'doctrine.cfg.json';
        $config = json_decode(file_get_contents($json));
        $paramsKey = (getenv('ENV') == 'development') ? 'development' : 'production';
        $config = $config->params->$paramsKey;
        return $config;
    }
}