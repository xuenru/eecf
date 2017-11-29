<?php
namespace Annonce\Mapper;

use Annonce\Model\User\User;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Sql;
use Zend\Stdlib\Hydrator\HydratorInterface;

class UserMapper
{
    protected $dbAdapter;
    protected $hydrator;
    protected $userProtoType;

    /**
     * AnnonceMapper constructor.
     */
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, User $user)
    {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->userProtoType = $user;
    }

    public function getAll($attribute = null, $value = null)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('annonce_user');
        $select->join(
            'annonce_role', 'annonce_user.role_id = annonce_role.id', array('role_name' => 'name'),
            $select::JOIN_LEFT
        );

        if ($attribute != null && $value != null) {
            $select->where(array("annonce_user.$attribute= ?" => $value));
        }

        $stmt = $sql->prepareStatementForSqlObject($select);

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->userProtoType);
            return $resultSet->initialize($result);
        }
        return array();
    }

    public function getById($id)
    {
        return $this->get('id', $id);
    }

    public function getByUsername($username)
    {
        return $this->get('username', $username);
    }

    public function getByRoleId($roleId)
    {
        return $this->getAll('role_id', $roleId);
    }

    public function get($attribute, $value)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('annonce_user');
        $select->join(
            'annonce_role', 'annonce_user.role_id = annonce_role.id', array('role_name' => 'name'),
            $select::JOIN_LEFT
        );
        $select->where(array("annonce_user.$attribute= ?" => $value));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->userProtoType);
        }

        throw new InvalidQueryException("User with $attribute: $value not found!");
    }
}