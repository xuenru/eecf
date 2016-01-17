<?php
namespace Annonce\Listener;

use Annonce\Model\Status;
use Annonce\Model\User\Role;
use Zend\Authentication\AuthenticationService;
use Zend\Cache\Storage\Adapter\Filesystem;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\EventManager\ListenerAggregateTrait;
use Zend\Http\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack;
use Zend\Mvc\Router\RouteMatch;
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Resource\GenericResource;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\Session\Container;

class CheckAuth implements ListenerAggregateInterface, ServiceLocatorAwareInterface
{
    use ListenerAggregateTrait;
    use ServiceLocatorAwareTrait;
    /** @var  Acl */
    protected $annonceAcl;

    public function attach(EventManagerInterface $events)
    {
        $this->listeners[] = $events->attach(MvcEvent::EVENT_ROUTE, array($this, 'checkAuth'));
    }

    public function checkAuth(EventInterface $event)
    {
        /** @var RouteMatch $match */
        $match = $event->getRouteMatch();
        $controllerArr = explode('\\', $match->getParam('controller'));
        $route = $controllerArr[0];
        if ($match instanceof RouteMatch && $route == 'Annonce') {
            $router = $event->getRouter();
            /** @var Response $response */
            $response = $event->getResponse();
            /** @var AuthenticationService $authService */
            $authService = $this->getServiceLocator()->get('Annonce\Service\Authentication')->getAuthService();
            if ($authService->hasIdentity()) {
                $username = $authService->getIdentity();
                $userService = $this->getServiceLocator()->get('Annonce\Service\UserService');
                $userObj = $userService->getUserByUsername($username);
                $userSessionContainer = new Container('user');
                $userSessionContainer->offsetSet('obj', $userObj);
                $this->_initAcl($event);
                if (!$this->_checkAnnonceAcl($event, $userObj->getRoleName())) {
                    return $this->_getResponse($router, $response, 'annonce');
                }
            }
            $matchedRouteName = $match->getMatchedRouteName();
            if (!$authService->hasIdentity() && $matchedRouteName != "annonce-user/login") {
                $sessionContainer = new Container('redirectUrl');
                $sessionContainer->offsetSet('routeMatch', $match);
                return $this->_getResponse($router, $response, 'annonce-user/login');
            }

        }

    }

    private function _getResponse($router, $response, $name)
    {
        /** @var TreeRouteStack $router */
        $url = $router->assemble(array(), array('name' => $name));

        $response->getHeaders()->addHeaderLine('Location', $url);
        $response->setStatusCode(302);

        return $response;
    }

    private function _initAcl(MvcEvent $e)
    {
        /** @var Filesystem $aclCache */
        $aclCache = $e->getApplication()->getServiceManager()->get('Zend\Cache\StorageFactory');
        $this->annonceAcl = $aclCache->getItem('annonce');

        if (!$this->annonceAcl instanceof Acl) {
            $acl = new Acl();

            $acl->addRole(new GenericRole(Role::ROLE_ADMIN));
            $acl->addRole(new GenericRole(Role::ROLE_EDITOR));
            $acl->addRole(new GenericRole(Role::ROLE_VALIDATOR));
            $acl->addRole(new GenericRole(Role::ROLE_PRINTER));
            $acl->addRole(new GenericRole(Role::ROLE_READER));

            $acl->addResource(new GenericResource('Annonce\Controller\List'));
            $acl->addResource(new GenericResource('Annonce\Controller\Write'));


            $acl->deny();

            $acl->allow('admin', null, null);
            $acl->allow(null, 'Annonce\Controller\List', 'index');
            $acl->allow(null, 'Annonce\Controller\Write', 'edit/r');
            $acl->allow('editor', 'Annonce\Controller\Write', 'add');
            $acl->allow('editor', 'Annonce\Controller\Write', 'edit/w');
            $acl->allow('editor', 'Annonce\Controller\Write', 'updateStatus/' . Status::STATUS_PENDING);
            $acl->allow(
                'validator', 'Annonce\Controller\Write',
                array('updateStatus/' . Status::STATUS_OK, 'updateStatus/' . Status::STATUS_KO)
            );
            $acl->allow('printer', 'Annonce\Controller\Write', 'updateStatus/' . Status::STATUS_CLOSED);

            $this->annonceAcl = $acl;
            $aclCache->setItem('annonce', $acl);
        }

    }

    protected function _checkAnnonceAcl(MvcEvent $e, $role)
    {
        $routeMatch = $e->getRouteMatch();
        $controller = $routeMatch->getParam('controller');
        $action = $routeMatch->getParam('action');

        $privilegs = $action;

        if ($action == 'edit') {
            $privilegs = $e->getRequest()->isPost() ? 'edit/w' : 'edit/r';
        } elseif ($action == 'updateStatus') {
            if (!$e->getRequest()->isPost()) {
                return true;
            }
            $annonceIdStatus = $e->getRequest()->getPost('annonce_id_status');
            $annonceIdStatusArr = explode('|', $annonceIdStatus);
            $status = $annonceIdStatusArr[1];
            $privilegs = "updateStatus/$status";
        }
        if ($this->annonceAcl->hasResource($controller)
            && !$this->annonceAcl->isAllowed(
                $role, $controller, $privilegs
            )
        ) {
            $serviceManager = $e->getApplication()->getServiceManager();
            $controllerPluginManager = $serviceManager->get('ControllerPluginManager');
            $flashMessenger = $controllerPluginManager->get('FlashMessenger');
            $flashMessenger->addErrorMessage('you have no right to do this!');
            return false;
        }

        return true;

    }
}