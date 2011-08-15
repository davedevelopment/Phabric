<?php

namespace Phabric;
use Mockery as m;


/**
 * Test class for Bus.
 * Generated by PHPUnit on 2011-07-31 at 12:12:00.
 */
class PhabricTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Phabric
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $db  = m::mock('\Doctrine\DBAL\Connection');
        $this->object = new Phabric($db);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        \Mockery::close();
    }
    
    public function testCreateEntity()
    {
        $entity = $this->object->createEntity('Event');
        
        $this->assertType('\Phabric\Entity', $entity);
    }
    
    public function testCreateEntityWithConfig()
    {
        $entity = $this->object->createEntity('Event', array('tableName' => 'event'));
        
        $this->assertType('\Phabric\Entity', $entity);
    }
    
    public function testCreateEntityHasRegisteredEntity()
    {
        $this->object->createEntity('Event');
            
        $entity = $this->object->getEntity('Event');
        
        $this->assertType('\Phabric\Entity', $entity);
    }

    /**
     * @todo Implement testRegisterNamedDataTranslation().
     */
    public function testAddDataTranslation()
    {
        $fn = function($a, $b) { $c = $a + $b; return $c; };

        $this->object->addDataTranslation('test', $fn);

        $this->assertEquals($fn, $this->object->getDataTranslation('test'));
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testAddDataTranslationNonCallableArgument()
    {
        $fn = array();

        $this->object->addDataTranslation('test', $fn);
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetDataTranslationNotFound()
    {
        $this->object->getDataTranslation('test');
    }

    /**
     * @expectedException InvalidArgumentException
     */
    public function testGetEntityNotFound()
    {
        $this->object->getEntity('anything');
    }
    
    public function testCreateFromTable() 
    {
        $tableData = array(
            array('name', 'datetime', 'venue', 'description'),
            array('PHPNW', '2011-10-08 09:00:00', 'Ramada Hotel', 'A Great Conf!')
        );


        $tableNode = m::mock('\Behat\Gherkin\Node\TableNode');
        
        $mockEntity = m::mock('\Phabric\Entity');
        
        $mockEntity->shouldReceive('createFromTable')
                   ->with($tableNode, null)
                   ->once();
        
        $this->object->addEntity('TEST', $mockEntity);
        
        $this->object->createFromTable('TEST', $tableNode);
       
    }
    

}

