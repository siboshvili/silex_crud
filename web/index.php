<?php

require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();
$app['debug'] = true;

$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'silex_crud',
        'user' => 'root',
        'passwod' => '',
        'charset' => 'utf8'
    )
));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.messages' => array(),
));
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => array(__DIR__.'/../views')
));
$app->register(new Silex\Provider\FormServiceProvider());

// მომხმარებლების ჩამონათვალი
$app->match('/user/list', function() use ($app) {
    $users = $app['db']->fetchAll('SELECT * FROM user');
    
    return $app['twig']->render('user/list.twig', array(
        'users' => $users
    ));
    
    //exit(var_dump($users));
})
->bind('user_list');

// მომხმარებლის დამატება
$app->match('/user/add', function(Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $form = $app['form.factory']->createBuilder('form')
            ->add('first_name', 'text', array(
                'label' => 'სახელი',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('last_name', 'text', array(
                'label' => 'გვარი',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('email', 'text', array(
                'label' => 'ელ-ფოსტა',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', 'submit', array(
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->getForm();
    
    $form->handleRequest($request);
    
    if ($form->isValid()) {
        $data = $form->getData();
        
        $app['db']->insert('user', array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ));
        
        return $app->redirect($app['url_generator']->generate('user_list'));
    }
    
    return $app['twig']->render('user/add.twig', array(
        'form' => $form->createView()
    ));
})
->bind('user_add');

// მომხმარებლის განახლება
$app->match('/user/edit/{id}', function(Symfony\Component\HttpFoundation\Request $request, $id) use ($app) {
    $user = $app['db']->fetchAssoc('SELECT * FROM user WHERE id = ?', array($id));
    
    $form = $app['form.factory']->createBuilder('form', $user)
            ->add('first_name', 'text', array(
                'label' => 'სახელი',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('last_name', 'text', array(
                'label' => 'გვარი',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('email', 'text', array(
                'label' => 'ელ-ფოსტა',
                'attr' => array(
                    'class' => 'form-control'
                )
            ))
            ->add('save', 'submit', array(
                'attr' => array(
                    'class' => 'btn btn-default'
                )
            ))
            ->getForm();
    
    $form->handleRequest($request);
    
    if ($form->isValid()) {
        $data = $form->getData();
        
        $app['db']->update('user', array(
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
        ), array('id' => $id));
        
        return $app->redirect($app['url_generator']->generate('user_list'));
    }
    
    return $app['twig']->render('user/edit.twig', array(
        'form' => $form->createView()
    ));
})
->bind('user_edit');

// მომხმარბლის წაშლა
$app->match('user/delete/{id}', function($id) use ($app) {
    $app['db']->delete('user', array('id' => $id));
    
    return $app->redirect($app['url_generator']->generate('user_list'));
})
->bind('user_delete'); 

$app->run();
