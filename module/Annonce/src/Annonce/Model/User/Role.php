<?php
namespace Annonce\Model\User;

class Role
{
    const ROLE_ADMIN_ID = 1;
    const ROLE_ADMIN = 'admin';

    const ROLE_EDITOR_ID = 2;
    const ROLE_EDITOR = 'editor';

    const ROLE_VALIDATOR_ID = 3;
    const ROLE_VALIDATOR = 'validator';

    const ROLE_PRINTER_ID = 4;
    const ROLE_PRINTER = 'printer';

    const ROLE_READER_ID = 5;
    const ROLE_READER = 'reader';

    /** @var  int */
    protected $id;
    /** @var  string */
    protected $name;
    /** @var  int */
    protected $status;

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
     * @return Role
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Role
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param int $status
     *
     * @return Role
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }
}