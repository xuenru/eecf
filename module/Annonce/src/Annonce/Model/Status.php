<?php
namespace Annonce\Model;

class Status
{
    const STATUS_EDIT = 1;
    const STATUS_EDIT_LABEL = 'edit';
    const STATUS_PENDING = 2;
    const STATUS_PENDING_LABEL = 'pending';
    const STATUS_OK = 3;
    const STATUS_OK_LABEL = 'ok';
    const STATUS_KO = 4;
    const STATUS_KO_LABEL = 'ko';
    const STATUS_CLOSED = 5;
    const STATUS_CLOSED_LABEL = 'closed';

    public static $STATUS
        = array(
            self::STATUS_EDIT    => self::STATUS_EDIT_LABEL,
            self::STATUS_PENDING => self::STATUS_PENDING_LABEL,
            self::STATUS_OK      => self::STATUS_OK_LABEL,
            self::STATUS_KO      => self::STATUS_KO_LABEL,
            self::STATUS_CLOSED  => self::STATUS_CLOSED_LABEL,
        );

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $label;

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
     * @return Status
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     *
     * @return Status
     */
    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    public static function canEdit($currentStatus)
    {

    }

}