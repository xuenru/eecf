<?php

namespace Annonce\Mapper;

use Annonce\Model\Annonce;
use Annonce\Model\History;
use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\Adapter\Exception\InvalidQueryException;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Delete;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Update;
use Zend\Session\Container;
use Zend\Stdlib\Hydrator\HydratorInterface;

class AnnonceMapper
{
    protected $dbAdapter;
    protected $hydrator;
    protected $annonceProtoType;

    protected $userObj;

    /**
     * AnnonceMapper constructor.
     */
    public function __construct(AdapterInterface $dbAdapter, HydratorInterface $hydrator, Annonce $annonce)
    {
        $this->dbAdapter = $dbAdapter;
        $this->hydrator = $hydrator;
        $this->annonceProtoType = $annonce;

        $userSessionContainer = new Container('user');
        $this->userObj = $userSessionContainer->offsetGet('obj');
    }

    public function getAll()
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('annonce_entity');
        $select->join(
            'annonce_status', 'annonce_entity.status = annonce_status.id', array('status_label' => 'label'),
            $select::JOIN_LEFT
        );
        $select->order('worship_date DESC');

        $stmt = $sql->prepareStatementForSqlObject($select);

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, $this->annonceProtoType);
            return $resultSet->initialize($result);
        }
        return array();
    }

    public function get($id)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('annonce_entity');
        $select->join(
            'annonce_status', 'annonce_entity.status = annonce_status.id', array('status_label' => 'label'),
            $select::JOIN_LEFT
        );
        $select->where(array('annonce_entity.id= ?' => $id));

        $stmt = $sql->prepareStatementForSqlObject($select);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult() && $result->getAffectedRows()) {
            return $this->hydrator->hydrate($result->current(), $this->annonceProtoType);
        }

        throw new InvalidQueryException("Annonce with ID: $id not found!");
    }

    public function save(Annonce $annonceObj)
    {
        $annonceObj->setStatus(Annonce::ANNONCE_STATUS_EDIT);
        $annonceData = $this->hydrator->extract($annonceObj);
        unset($annonceData['id']); // Neither Insert nor Update needs the ID in the array
        unset($annonceData['status_label']);
        unset($annonceData['array_copy']);


        if ($annonceObj->getId()) {
            // ID present, it's an Update
            $action = new Update('annonce_entity');
            $action->set($annonceData);
            $action->where(array('id = ?' => $annonceObj->getId()));
        } else {
            // ID NOT present, it's an Insert
            $action = new Insert('annonce_entity');
            $action->values($annonceData);
        }
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $annonceObj->setId($newId);
            }

            $this->_updateHistory($annonceObj->getId(), $annonceObj->getStatus());

            return $annonceObj;
        }

        throw new \Exception("Database error");
    }

    public function delete($id)
    {
        $action = new Delete('annonce_entity');
        $action->where(array('id = ?' => $id));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        $affectedRows = (bool)$result->getAffectedRows();
        if ($affectedRows) {
            $this->_updateHistory($id, Annonce::ANNONCE_STATUS_DELETED);
        }
        return $affectedRows;
    }

    public function updateStatus($annonceId, $statusId, $comment = null)
    {
        $action = new Update('annonce_entity');
        $action->set(array('status' => $statusId));
        $action->where(array('id = ?' => $annonceId));

        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();
        $affectedRows = (bool)$result->getAffectedRows();
        if ($affectedRows) {
            $this->_updateHistory($annonceId, $statusId, $comment);
        }
        return $affectedRows;
    }

    private function _updateHistory($annonceId, $statusId, $comment = null)
    {
        $annonceObj = new History();
        $historyData = array(
            'annonce_id' => $annonceId,
            'status_to'  => $statusId,
            'comment'    => $comment,
            'author'     => $this->userObj->getId(),
            'update_at'  => date('Y-m-d H:i:s')
        );
        $action = new Insert('annonce_history');
        $action->values($historyData);
        $sql = new Sql($this->dbAdapter);
        $stmt = $sql->prepareStatementForSqlObject($action);
        $result = $stmt->execute();

        if ($result instanceof ResultInterface) {
            if ($newId = $result->getGeneratedValue()) {
                // When a value has been generated, set it on the object
                $annonceObj->setId($newId);
            }

            return $annonceObj;
        }

        throw new \Exception("Database error");
    }

    public function getHistories($annonceId)
    {
        $sql = new Sql($this->dbAdapter);
        $select = $sql->select('annonce_history');
        $select->join(
            'annonce_user', 'annonce_history.author = annonce_user.id',
            array('author_lastname' => 'lastname', 'author_firstname' => 'firstname'),
            $select::JOIN_LEFT
        );
        $select->join(
            'annonce_status', 'annonce_history.status_to = annonce_status.id', array('statusToLabel' => 'label'),
            $select::JOIN_LEFT
        );
        $select->where(array('annonce_id = ?' => $annonceId));
        $select->order('update_at DESC');

        $stmt = $sql->prepareStatementForSqlObject($select);

        $result = $stmt->execute();

        if ($result instanceof ResultInterface && $result->isQueryResult()) {
            $resultSet = new HydratingResultSet($this->hydrator, new History());
            return $resultSet->initialize($result);
        }
        return array();
    }
}