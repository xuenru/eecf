<?php
namespace Annonce\Service;

use Annonce\Mapper\UserMapper;

class UserService
{
    protected $userMapper;

    public function __construct(UserMapper $userMapper)
    {
        $this->userMapper = $userMapper;
    }

    public function getAllUsers()
    {
        return $this->userMapper->getAll();
    }

    public function getUser($id)
    {
        return $this->userMapper->getById($id);
    }

    public function getUserByUsername($username)
    {
        return $this->userMapper->getByUsername($username);
    }

    public function getUsersByRole($roleId)
    {
        return $this->userMapper->getByRoleId($roleId);
    }
}