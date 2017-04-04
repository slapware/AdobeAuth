<?php
namespace Common\Model;

use Zend\Db\Adapter\Adapter;
//use Zend\Db\TableGateway\AbstractTableGateway;
//use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Sql\Expression;
//use Zend\InputFilter\InputFilter;
//use Zend\InputFilter\Factory as InputFactory;
use Zend\Db\TableGateway\TableGateway;

//class UsersTable extends AbstractTableGateway implements AdapterAwareInterface
class SourceTable
{
    protected $table = 'source';
    
    protected $tableGateway;
    
    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }
    /**
     * Set db adapter
     *
     * @param Adapter $adapter
     */
    public function setDbAdapter(Adapter $adapter)
    {
        $this->adapter = $adapter;
        $this->initialize();
    }
    
    /**
     * Method to get users by email
     *
     * @param string $email
     * @return ArrayObject
     */
    public function getByUsername($email)
    {
        $rowset = $this->tableGateway->select(array('email' => $email));
        
        return $rowset->current();
    }
    
    /**
     * Method to get users by id
     *
     * @param int $id
     * @return ArrayObject
     */
    public function getById($id)
    {
        $rowset = $this->tableGateway->select(array('id' => $id));
        
        return $rowset->current();
    }
    
    /**
     * Method to check if user is registered
     *
     * @param $email $passwd
     * @return int
     */
    public function getUserCount($email, $passwd)
    {
    	$result = $this->tableGateway->select(array('email' => $email), array('password' => md5($passwd)));
		return $result;
    }
    
    /**
     * Method to create a new user on the DB
     *
     * @param array $userData
     * @return int
     */
    public function create($userData)
    {
    	$this->tableGateway->insert($userData);
        return $this->tableGateway->getLastInsertValue();
    }
    
    
}