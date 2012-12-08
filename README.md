Driven
======
Driven is a command line tool for generating a PHP project skeleton
that is ready for TDD and DDD(domain driven design).

Directory Structure
-------------------
Driven will create a directory structure that nicely supports a layered architecture.

```bash
├── bin
|   └── doctrine
├── functional
|   └── Driven
├── it
|   └── Driven
|       ├── Infrastructure
|       |   └── Persistence
|       |       └── Doctrine
|       |           └── DoctrineTest.php
|       ├── datasets
|       └── DbTest.php
├── src
|   └── Driven
|       ├── Domain
|       |   ├── Model
|       |   |   └── Repository.php
|       |   └── Service
|       └── Infrastructure
|           └── Persistence
|               └── Doctrine
|                   ├── mappings
|                   ├── proxies
|                   ├── ConfigurationFactory.php
|                   ├── EntityManagerFactory.php
|                   ├── RepositoryBase.php
|                   ├── UnitOfWork.php
|                   └── doctrine.cfg.json
├── test
|   ├── Driven
|   |   ├── Domain
|   |   |   ├── Model
|   |   |   └── Service
|   |   └── TestBase.php
|   ├── fixtures
|   └── bootstrap.php
├── composer.json
└── phpunit.xml.dist
```

Where `Driven` would be replaced with your supplied namespace/vendor dir.

composer.json
-------------
Driven comes with a composer.json file that loads the following dependencies: phpunit, phpunit/dbunit, and doctrine/orm. 
The composer.json file will also correctly configure the composer autoloader for use with the source and test directories.

Testing Classes
---------------
### TestBase.php ###
A base test case for unit testing. It contains helpers for loading fixtures from the `fixtures` directory, and has methods for getting and setting private/protected properties.

### DbTest.php ###
A base test case that extends PHPUnit's Database_TestCase extension. Contains all the behavior available in TestBase.php.

It takes advantage of PDO, and the dsn can be configured on a per test basis by overriding the default dsn:

```php
protected $dsn = "pgsql:host=%s;dbname=%s;user=%s;password=%s";
```

Additionally you can override the default schema name by overriding the schema property in tests:

```php
protected $schema = ":dbtest:";
```

In addition, there is a helper that can be called to load a dataset from xml into the database using the PHPUnit provided method `getDataSet`. It will look for these datasets in the datasets directory contained in the integration test suite: `it`

```php
public function getDataSet()
{
    return $this->dataset('dummy-data.xml');
}
```
For more information on using the PHPUnit database extension, take a look [here.](http://www.phpunit.de/manual/current/en/database.html)

### DoctrineTest.php ###
This base test case extends DbTest, and takes advantage of some Doctrine2 tools to make testing easier. It makes use of the UnitOfWork to test a typical work flow in a web application. It will create a schema for testing based on the list of supplied classes. Entity classes are supplied via the $classes property.

```php
protected $classes = array('Driven\\Domain\\Model\\Entity\\Entity');
```

If the corresponding mapping files exist in the `mappings` directory, the schema for the given entities will be torn down and created before each test.

Configurations are read from doctrine.cfg.json, so make sure you set the proper environment variable before running PHPUnit.

`ENV=development vendor/bin/phpunit it`

All test suites and the bootstrap file that loads the composer autoloader are referenced in the phpunit.xml.dist file.

Persistence Classes
-------------------
All persistence related classes are kept in the `Doctrine` directory. The components have been tested and used as part of the project located [here.](https://github.com/brianium/php-classic-blog)

### mappings ###
This directory stores the xml mappings used with doctrine. It comes with a sample file to demonstrate conventions.

### proxies ###
This is where doctrine will look for proxy classes. These should be generated using the doctrine console.

### ConfigurationFactory.php ###
This class is used to generate configurations for doctrine based on the ENV environment variable (development or production).

### EntityManagerFactory.php ###
Used for creating or getting a singleton EntityManager. The workhorse for the packaged UnitOfWork and Repository.

### RepositoryBase.php ###
A handy base for all entity repositories to extend. Includes methods to fetch all entities, fetch a single entity by id, fetch multiple entities by condition, persist an entity, and delete an entity. The only requirement is that subclasses specify the entity type they are persisting:

```php
class MyEntityRepository extends RepositoryBase
{
    protected $type = 'Driven\\Domain\\Model\\MyEntity\\MyEntity';
}
```

### UnitOfWork.php ###
A unit of work for transactions to take place within. Contains your standard begin, commit, and rollback methods.

### doctrine.cfg.json ###
A json file for configuring credentials for development and production.

### Packaged doctrine console ###
A fully functional doctrine console comes packaged in the `bin` directory. Some tweaking may be required depending on your OS to make it executable. Nix users can simply do the following `chmod +x doctrine`.

Usage
-----
![Driven Usage](https://raw.github.com/brianium/driven/master/driven-usage.png "Driven Console Usage")

Driven will create a project structure inside the current working directory. The only available option is to specify the composer binary. By default composer is assumed to be globally installed as `composer`.

If you have a composer.phar , you can use it with driven like so:

`driven -c "php composer.phar" MyProject`

Installation
------------
Driven can be installed via composer. Just add the following to your composer.json file:
```js
"require": {
    "brianium/driven": "dev-master"
}
```
Then run `php composer.phar install`

You can also clone the repository directly from github.

After installation it may be helpful to setup a symbolic link so you have access to driven globally.

```bash
sudo ln -s /path/to/driven/bin/driven /usr/bin/driven
```
Then you should be able to access driven anywhere:

```bash
driven MyNewAwesomeDomainDrivenProject
```

Example project coming soon.