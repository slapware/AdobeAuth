<?php
namespace Common\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;

//class UsersTable extends AbstractTableGateway implements AdapterAwareInterface
class DownLoadView
{
    protected $table = 'downloads';
    
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
        
        return $rowset;
    }

    /**
     * Method to get account by uuid
     *
     * @param string $email
     * @return ArrayObject
     */
    public function getInfo($user_id)
    {
    	$rowset = $this->tableGateway->select(array('user_id' => $user_id));
    
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
    public function getUserCount($email)
    {
    	$result = $this->tableGateway->select(array('email' => $email));
		return $result->current();
    }
    
    
}