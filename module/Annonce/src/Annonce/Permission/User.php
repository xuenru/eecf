<?php

namespace Annonce\Permission;

use Annonce\Model\Annonce;
use Annonce\Model\User\Role;

class User
{
    public static function canAdd($roleId)
    {
        if ($roleId == Role::ROLE_EDITOR_ID || $roleId == Role::ROLE_ADMIN_ID) {
            return true;
        }
        return false;
    }

    public static function canEdit($roleId, $currentAnnonceStatus)
    {
        if (($roleId == Role::ROLE_EDITOR_ID
                && in_array(
                    $currentAnnonceStatus, array(
                        Annonce::ANNONCE_STATUS_EDIT,
                        Annonce::ANNONCE_STATUS_KO
                    )
                ))
            || self::adminRight($roleId)
        ) {
            return true;
        }

        return false;
    }

    public static function canValidate($roleId, $currentAnnonceStatus)
    {
        if (($roleId == Role::ROLE_VALIDATOR_ID && $currentAnnonceStatus == Annonce::ANNONCE_STATUS_PENDING)
            || self::adminRight($roleId)
        ) {
            return true;
        }

        return false;
    }

    public static function canRead($roleId, $currentAnnonceStatus)
    {
        if (($roleId == Role::ROLE_READER_ID && $currentAnnonceStatus == Annonce::ANNONCE_STATUS_OK)
            || self::adminRight(
                $roleId
            )
        ) {
            return true;
        }

        return false;
    }

    public static function canClose($roleId, $currentAnnonceStatus)
    {
        if (($roleId == Role::ROLE_PRINTER_ID && $currentAnnonceStatus == Annonce::ANNONCE_STATUS_OK)
            || self::adminRight(
                $roleId
            )
        ) {
            return true;
        }

        return false;
    }

    public static function canDelete($roleId)
    {
        if (self::adminRight($roleId)) {
            return true;
        }

        return false;
    }

    public static function adminRight($roleId)
    {
        if ($roleId == Role::ROLE_ADMIN_ID) {
            return true;
        }

        return false;
    }

    public static function encryptPassword($pw)
    {
        $salt = 'JesusLovesYou!';
        return md5($salt . $pw);
    }
}