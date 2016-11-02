<?php

use G\Services\User\CreateUser;
use G\Services\User\DeleteUser;
use G\Services\User\ReadUser;
use G\Services\User\UpdateUser;

require __DIR__.'/../vendor/autoload.php';

$app = new Slim\App();

$container = $app->getContainer();

$container[ReadUser::class] = function ($c) {
  return new ReadUser();
};

$container[DeleteUser::class] = function ($c) {
    return new DeleteUser();
};

$container[CreateUser::class] = function ($c) {
    return new CreateUser();
};

$container[UpdateUser::class] = function ($c) {
    return new DeleteUser();
};

$app->get('/user/{id}', ReadUser::class);
$app->delete('/user/{id}', DeleteUser::class);
$app->post('/user', CreateUser::class);
$app->put('/user/{id}', UpdateUser::class);


$app->run();
