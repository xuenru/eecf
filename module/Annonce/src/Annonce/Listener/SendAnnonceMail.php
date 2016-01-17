<?php
namespace Annonce\Listener;

use Annonce\Controller\WriteController;
use Annonce\Model\Status;
use Annonce\Model\User\Role;
use Annonce\Service\AnnonceService;
use Annonce\Service\UserService;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Mail\Message;
use Zend\ServiceManager\ServiceLocatorInterface;

class SendAnnonceMail implements ListenerAggregateInterface
{
    use ListenerAggregateTrait;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->getSharedManager()->attach('*', 'updateStatusAction', array($this, 'sendmail'));
    }

    public function sendmail(EventInterface $e)
    {
        $newAnnonceStatusId = $e->getParam('annonceStatus');

        /** @var WriteController $target */
        $target = $e->getTarget();
        $serviceLocator = $target->getServiceLocator();
        $uri = $target->getRequest()->getUri();
        $baseUrl = sprintf('%s://%s', $uri->getScheme(), $uri->getHost());

        switch ($newAnnonceStatusId) {
            case Status::STATUS_PENDING:
                $toMailList = $this->_getToEmailList(Role::ROLE_VALIDATOR_ID, $serviceLocator);
                $subject = 'Annonce to be validated';
                $contentMsg = 'is waiting for validation';
                break;
            case Status::STATUS_OK:
                $toMailList = $this->_getToEmailList(Role::ROLE_PRINTER_ID, $serviceLocator);
                $subject = 'Annonce is ok';
                $contentMsg = 'is OK';
                break;
            case Status::STATUS_KO:
                $toMailList = $this->_getToEmailList(Role::ROLE_EDITOR_ID, $serviceLocator);
                $subject = 'Annonce is ko';
                $contentMsg = 'is KO';
                break;
            default:
                return;
        }

        $annonceId = $e->getParam('annonceId');
        /** @var AnnonceService $annonceService */
        $annonceService = $serviceLocator->get('Annonce\Service\AnnonceService');
        $annonceName = $annonceService->getAnnonce($annonceId)->getWorshipDate();
        $annonceComment = 'Comment: ' . $e->getParam('annonceHistortyComment');
        $updateAuthor = $e->getParam('updateAuthor');
        $annonceUrl = $baseUrl . $target->url()->fromRoute('annonce/edit', array('id' => $annonceId));

        $msgBody
            = "Hello,\n
            The annonce of worship $annonceName $contentMsg,\n
            Request by $updateAuthor, check it by the link: $annonceUrl,\n
            $annonceComment,\n
            \n
            EECF Annonce Service
            ";

        $message = new Message();
        $config = $serviceLocator->get('Config');
        $fromEmail = $config['mail']['transport']['options']['connection_config']['username'];
        $message->addFrom($fromEmail, 'EECF Annonce Notice')
            ->addTo($toMailList)
            ->setSubject($subject)
            ->setBody($msgBody);

        $transport = $serviceLocator->get('mail.transport');
        $transport->send($message);
    }

    private function _getToEmailList($roleId, ServiceLocatorInterface $serviceLocator)
    {
        /** @var UserService $userService */
        $userService = $serviceLocator->get('Annonce\Service\UserService');

        $users = $userService->getUsersByRole($roleId);

        $toEmailList = array();

        foreach ($users as $user) {
            $toEmailList[$user->getEmail()] = $user->getLastname() . ' ' . $user->getFirstname();
        }

        return $toEmailList;
    }

}
