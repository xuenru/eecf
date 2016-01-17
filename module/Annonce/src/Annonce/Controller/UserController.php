<?php
namespace Annonce\Controller;

use Annonce\Permission\User;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;

class UserController extends AbstractActionController
{
    protected $userObj = null;

    public function __construct()
    {
        $userSessionContainer = new Container('user');
        $this->userObj = $userSessionContainer->offsetGet('obj');
    }

    public function infoAction()
    {
        return array('user' => $this->userObj);
    }

    public function loginAction()
    {
        if ($this->userObj) {
            $this->redirect()->toRoute('annonce-user/info');
        }
        $serviceLocator = $this->getServiceLocator();
        $loginForm = $serviceLocator->get('formElementManager')->get('Annonce\Form\LoginForm');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $loginInputFilter = $serviceLocator
                ->get('inputFilterManager')
                ->get('Annonce\InputFilter\LoginFormInputFilter');
            $loginForm->setInputFilter($loginInputFilter);

            $loginForm->setData($request->getPost());

            if ($loginForm->isValid()) {
                $userEntity = $loginForm->getData();
                $auth = $this->getServiceLocator()->get('Annonce\Service\Authentication');

                $authService = $auth->getAuthService();
                $authService->getAdapter()
                    ->setIdentity($userEntity['username'])
                    ->setCredential(User::encryptPassword($userEntity['password']));
                $result = $authService->authenticate();
                if ($result->isValid()) {
                    if (isset($userEntity['remember_me']) && $userEntity['remember_me']) {
                        $authService->getStorage()->setRememberMe();
                    }

                    $sessionContainer = new Container('redirectUrl');
                    $redirectRouteMatch = $sessionContainer->offsetGet('routeMatch');

                    if (!$redirectRouteMatch) {
                        return $this->redirect()->toRoute('annonce-user/info');
                    }

                    $sessionContainer->offsetUnset('routeMatch');
                    return $this->redirect()->toRoute(
                        $redirectRouteMatch->getMatchedRouteName(),
                        $redirectRouteMatch->getParams()
                    );

                } else {
                    $this->flashMessenger()->addErrorMessage('login ko!');
                }
            }

        }

        return array('form' => $loginForm);
    }

    public function logoutAction()
    {
        $auth = $this->getServiceLocator()->get('Annonce\Service\Authentication');
        $authService = $auth->getAuthService();
        $authService->getStorage()->forgetMe();
        $authService->getStorage()->clear();
        $userSessionContainer = new Container('user');
        $userSessionContainer->getManager()->getStorage()->clear();
        $this->redirect()->toRoute('annonce');
    }

    public function registerAction()
    {

    }

}