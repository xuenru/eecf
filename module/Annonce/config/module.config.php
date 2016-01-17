<?php

return array(
    'router'          => array(
        'routes' => array(
            'annonce'      => array(
                'type'          => 'literal',
                'options'       => array(
                    'route'    => '/annonce',
                    'defaults' => array(
                        'controller' => 'Annonce\Controller\List',
                        'action'     => 'index'
                    )
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'edit'          => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'       => '/edit[/:id]',
                            'defaults' => array(
                                'controller' => 'Annonce\Controller\Write',
                                'action'     => 'edit'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                    'add'  => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/add',
                            'defaults' => array(
                                'controller' => 'Annonce\Controller\Write',
                                'action'     => 'add'
                            )
                        )
                    ),
                    'delete' => array(
                        'type'    => 'segment',
                        'options' => array(
                            'route'       => '/delete/:id',
                            'defaults' => array(
                                'controller' => 'Annonce\Controller\List',
                                'action'     => 'delete'
                            ),
                            'constraints' => array(
                                'id' => '\d+'
                            )
                        )
                    ),
                    'update-status' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/update-status',
                            'defaults' => array(
                                'controller' => 'Annonce\Controller\Write',
                                'action'     => 'updateStatus'
                            )
                        )
                    )
                )
            ),
            'annonce-user' => array(
                'type'          => 'literal',
                'options' => array(
                    'route'    => '/annonce-user',
                    'defaults' => array(
                        'controller' => 'Annonce\Controller\User',
                        'action'     => 'info'
                    )
                ),
                'may_terminate' => true,
                'child_routes'  => array(
                    'info'   => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/info',
                            'defaults' => array(
                                'controller' => 'Annonce\Controller\User',
                                'action'     => 'info'
                            )
                        )
                    ),
                    'login' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/login',
                            'defaults' => array(
                                'controller' => 'Annonce\Controller\User',
                                'action'     => 'login'
                            )
                        )
                    ),
                    'logout' => array(
                        'type'    => 'literal',
                        'options' => array(
                            'route'    => '/logout',
                            'defaults' => array(
                                'controller' => 'Annonce\Controller\User',
                                'action'     => 'logout'
                            )
                        )
                    )
                )
            )
        )
    ),
    'controllers'     => array(
        'factories'  => array(
            'Annonce\Controller\List'  => 'Annonce\Factory\ListControllerFactory',
            'Annonce\Controller\Write' => 'Annonce\Factory\WriteControllerFactory'
        ),
        'invokables' => array(
            'Annonce\Controller\User' => 'Annonce\Controller\UserController'
        )
    ),
    'view_manager'    => array(
        'template_path_stack' => array(
            __DIR__ . '/../view'
        )
    ),
    'service_manager' => array(
        'factories'  => array(
            'Zend\Db\Adapter\Adapter'        => 'Zend\Db\Adapter\AdapterServiceFactory',
            'Annonce\Service\AnnonceService' => 'Annonce\Factory\AnnonceServiceFactory',
            'Annonce\Mapper\AnnonceMapper'   => 'Annonce\Factory\AnnonceMapperFactory',
            'Annonce\Service\UserService'    => 'Annonce\Factory\UserServiceFactory',
            'Annonce\Mapper\UserMapper'      => 'Annonce\Factory\UserMapperFactory',
            'Zend\Cache\StorageFactory'      => function () {
                return \Zend\Cache\StorageFactory::factory(
                    array(
                        'adapter' => array(
                            'name'    => 'filesystem',
                            'options' => array(
                                'namespace' => 'eecf',
                                'cacheDir'  => 'data/cache'
                            ),
                        ),
                        'plugins' => array('serializer'),
                    )
                );
            },
            'AnnonceFormInputFilter'         => function ($sm) {
                $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                return new \Annonce\InputFilter\AnnonceFormInputFilter($dbAdapter);
            },
            'mail.transport'                 => function ($sm) {
                $config = $sm->get('Config');
                $transport = new \Zend\Mail\Transport\Smtp();
                $transport->setOptions(new \Zend\Mail\Transport\SmtpOptions($config['mail']['transport']['options']));

                return $transport;
            },
        ),
        'invokables' => array(
            'Annonce\Service\Authentication'   => 'Annonce\Service\Authentication',
            'Annonce\Listener\CheckAuth'     => 'Annonce\Listener\CheckAuth',
            'Annonce\Listener\SendAnnonceMail' => 'Annonce\Listener\SendAnnonceMail'
        )
    ),
    'translator' => array(
        'locale' => 'zh_CN'
    )
);