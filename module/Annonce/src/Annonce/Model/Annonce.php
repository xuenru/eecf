<?php
namespace Annonce\Model;

/**
 * Class Annonce
 *
 * @package Annonce\Model
 */
class Annonce
{
    /** @var  int */
    protected $id;
    /** @var  string */
    protected $worshipDate;
    /** @var  string */
    protected $message;
    /** @var  int */
    protected $author;
    /** @var  int */
    protected $validatedBy;
    /** @var  Status */
    protected $status;
    /** @var  string */
    protected $statusLabel;

    const ANNONCE_STATUS_EDIT = 1;
    const ANNONCE_STATUS_PENDING = 2;
    const ANNONCE_STATUS_OK = 3;
    const ANNONCE_STATUS_KO = 4;
    const ANNONCE_STATUS_CLOSED = 5;
    const ANNONCE_STATUS_DELETED = 6;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     *
     * @return Annonce
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getWorshipDate()
    {
        return $this->worshipDate;
    }

    /**
     * @param string $worshipDate
     *
     * @return Annonce
     */
    public function setWorshipDate($worshipDate)
    {
        $this->worshipDate = $worshipDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param string $message
     *
     * @return Annonce
     */
    public function setMessage($message)
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return int
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @param int $author
     *
     * @return Annonce
     */
    public function setAuthor($author)
    {
        $this->author = $author;
        return $this;
    }

    /**
     * @return int
     */
    public function getValidatedBy()
    {
        return $this->validatedBy;
    }

    /**
     * @param int $validatedBy
     *
     * @return Annonce
     */
    public function setValidatedBy($validatedBy)
    {
        $this->validatedBy = $validatedBy;
        return $this;
    }

    /**
     * @return Status
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param Status $status
     *
     * @return Annonce
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return string
     */
    public function getStatusLabel()
    {
        return $this->statusLabel;
    }

    /**
     * @param string $statusLabel
     *
     * @return Annonce
     */
    public function setStatusLabel($statusLabel)
    {
        $this->statusLabel = $statusLabel;
        return $this;
    }

    public function exchangeArray($data)
    {
        $this->worshipDate = isset($data['worship_date']) ? $data['worship_date'] : null;
        $this->message = isset($data['message']) ? $data['message'] : null;
    }

    public function getArrayCopy()
    {
        return array(
            'id'           => $this->getId(),
            'worship_date' => $this->worshipDate,
            'message'      => $this->message
        );
    }

    private function _changeDateFormatFr2Db($date)
    {
        if (preg_match('/^(\d{2})\/(\d{2})\/(\d{4})$/', $date, $matches)) {
            return "$matches[3]-$matches[2]-$matches[1]";
        }
        return $date;
    }

    private function _changeDateFormatDb2Fr($date)
    {
        if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $date, $matches)) {
            return "$matches[3]/$matches[2]/$matches[1]";
        }
        return $date;
    }

    public function findTextBgColorClass()
    {
        switch ($this->status) {
            case Status::STATUS_EDIT:
                return 'bg-primary';
            case Status::STATUS_PENDING:
                return 'bg-info';
            case Status::STATUS_OK:
                return 'bg-success';
            case Status::STATUS_KO:
                return 'bg-warning';
            default :
                return '';
        }
    }


}