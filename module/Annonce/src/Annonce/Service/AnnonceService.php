<?php
namespace Annonce\Service;

use Annonce\Mapper\AnnonceMapper;
use Annonce\Model\Annonce;

/**
 * Class AnnonceService
 *
 * @package Annonce\Service
 */
class AnnonceService
{
    /**
     * @var AnnonceMapper
     */
    protected $annonceMapper;

    /**
     * AnnonceService constructor.
     *
     * @param AnnonceMapper $annonceMapper
     */
    public function __construct(AnnonceMapper $annonceMapper)
    {
        $this->annonceMapper = $annonceMapper;
    }

    /**
     * @return array|\Zend\Db\ResultSet\ResultSet
     */
    public function getAllAnnonces()
    {
        return $this->annonceMapper->getAll();
    }

    /**
     * @param $id
     *
     * @return object
     */
    public function getAnnonce($id)
    {
        return $this->annonceMapper->get($id);
    }

    /**
     * @param Annonce $annonce
     */
    public function saveAnnonce(Annonce $annonce)
    {
        return $this->annonceMapper->save($annonce);
    }

    /**
     * @param $id
     */
    public function deleteAnnonce($id)
    {
        return $this->annonceMapper->delete($id);
    }

    public function updateAnnonceStatus($annonceId, $statusId, $comment = null)
    {
        return $this->annonceMapper->updateStatus($annonceId, $statusId, $comment);
    }

    public function getHistories($annonceId)
    {
        return $this->annonceMapper->getHistories($annonceId);
    }
}