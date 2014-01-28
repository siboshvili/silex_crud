<?php

$app->match('/user/list', 'Controller\\UserController::listAction')->bind('user_list');
$app->match('/user/add', 'Controller\\UserController::addAction')->bind('user_add');
$app->match('/user/edit/{id}', 'Controller\\UserController::editAction')->bind('user_edit');
$app->match('/user/delete/{id}', 'Controller\\UserController::deleteAction')->bind('user_delete');

return $app;