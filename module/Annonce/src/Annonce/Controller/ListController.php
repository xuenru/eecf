<?php
namespace Annonce\Controller;

use Annonce\Service\AnnonceService;
use Zend\Mvc\Controller\AbstractActionController;

class ListController extends AbstractActionController
{
    protected $annonceService;

    /**
     * ListController constructor.
     */
    public function __construct(AnnonceService $annonceService)
    {
        $this->annonceService = $annonceService;
    }

    public function indexAction()
    {
        $allAnnonces = $this->annonceService->getAllAnnonces();
        return array('annonces' => $allAnnonces);
    }

    public function deleteAction()
    {
        $id = $this->params('id');
        try {
            $this->annonceService->deleteAnnonce($id);
            return $this->redirect()->toRoute('annonce');
        } catch (\Exception $e) {
            $this->flashMessenger()->addErrorMessage('delete annonce error!');
        }
    }
}