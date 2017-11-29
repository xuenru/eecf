<?php
namespace Annonce\Controller;

use Annonce\Form\AnnonceForm;
use Annonce\Model\Annonce;
use Annonce\Permission\User;
use Annonce\Service\AnnonceService;
use Zend\Http\PhpEnvironment\Request;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

class WriteController extends AbstractActionController
{
    protected $annonceService;
    protected $annonceForm;
    /** @var \Annonce\Model\User\User */
    protected $userObj;

    /**
     * WriteController constructor.
     */
    public function __construct(AnnonceService $annonceService, AnnonceForm $annonceForm)
    {
        $this->annonceService = $annonceService;
        $this->annonceForm = $annonceForm;

        $userSessionContainer = new Container('user');
        $this->userObj = $userSessionContainer->offsetGet('obj');
    }

    public function editAction()
    {
        $request = $this->getRequest();
        $annonceId = $this->params('id');
        if (!$annonceId) {
            return $this->redirect()->toRoute('annonce/add');
        }
        /** @var Annonce $annonce */
        $annonce = $this->annonceService->getAnnonce($annonceId);
        $this->annonceForm->bind($annonce);

        if ($request->isPost()) {
            $this->annonceForm->setData($request->getPost());
            if ($this->annonceForm->isValid()) {
                try {
                    $this->annonceService->saveAnnonce($annonce);
                    //return $this->redirect()->toRoute('annonce');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('save annonce error!');
                }
            }
        }

        $annonceHistories = $this->annonceService->getHistories($annonceId);

        $canEdit = User::canEdit($this->userObj->getRoleId(), $annonce->getStatus());
        return array('form'             => $this->annonceForm,
                     'annonceId'        => $annonceId,
                     'status'           => $annonce->getStatusLabel(),
                     'statusId'         => $annonce->getStatus(),
                     'textColorClass'   => $annonce->findTextBgColorClass(),
                     'annonceHistories' => $annonceHistories,
                     'canEdit'          => $canEdit);
    }

    public function addAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $this->annonceForm->setData($request->getPost());
            if ($this->annonceForm->isValid()) {
                try {
                    $annonce = new Annonce();
                    $annonce->setAuthor($this->userObj->getId());
                    $annonceFormData = $this->annonceForm->getData();
                    $annonce->exchangeArray($annonceFormData);

                    $this->annonceService->saveAnnonce($annonce);
                    return $this->redirect()->toRoute('annonce');
                } catch (\Exception $e) {
                    $this->flashMessenger()->addErrorMessage('add annonce error!');
                }
            }
        }
        return array('form' => $this->annonceForm);
    }

    public function updateStatusAction()
    {
        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $annonceIdStatus = $request->getPost('annonce_id_status');
            $annonceIdStatusArr = explode('|', $annonceIdStatus);
            $annonceId = $annonceIdStatusArr[0];
            $annonceStatus = $annonceIdStatusArr[1];
            $annonceHistortyComment = $request->getPost('comment');
            try {
                $this->annonceService->updateAnnonceStatus($annonceId, $annonceStatus, $annonceHistortyComment);

                $updateAuthor = $this->userObj->getLastname() . ' ' . $this->userObj->getFirstname();

                $emailParams = compact('annonceId', 'annonceStatus', 'annonceHistortyComment', 'updateAuthor');
                $this->getEventManager()->trigger(__FUNCTION__, $this, $emailParams);

                return $this->redirect()->toRoute('annonce');
            } catch (\Exception $e) {
                $this->flashMessenger()->addErrorMessage('update annonce status error!');

            }
        }

        $this->redirect()->toRoute('annonce');
    }

}