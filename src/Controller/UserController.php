<?php

namespace Controller;

class UserController
{
    public function listAction(\Silex\Application $app)
    {
        $users = $app['db']->fetchAll('SELECT * FROM user');

        return $app['twig']->render('user/list.twig', array(
            'users' => $users
        ));
    }
    
    public function addAction(\Silex\Application $app, \Symfony\Component\HttpFoundation\Request $request)
    {
        $form = $app['form.factory']->create(new \Form\UserType());
    
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
    }
    
    public function editAction(\Silex\Application $app, \Symfony\Component\HttpFoundation\Request $request, $id)
    {
        $user = $app['db']->fetchAssoc('SELECT * FROM user WHERE id = ?', array($id));

        $form = $app['form.factory']->create(new \Form\UserType(), $user);

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
    }
    
    public function deleteAction(\Silex\Application $app, $id)
    {
        $app['db']->delete('user', array('id' => $id));

        return $app->redirect($app['url_generator']->generate('user_list'));  
    }
}