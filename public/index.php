<?php

use G\Services\User\CreateUser;
use G\Services\User\DeleteUser;
use G\Services\User\ReadUser;
use G\Services\User\UpdateUser;

require __DIR__.'/../vendor/autoload.php';

$dotenv = new Dotenv\Dotenv(__DIR__."/../");
$dotenv->load();


$app = new Slim\App(array("settings" => array("displayErrorDetails" => false)));

$container = $app->getContainer();

$container['pdo'] = function ($c) {
    $host = getenv('DBHOST');
    $username = getenv('DBUSERNAME');
    $password = getenv('DBPASSWORD');
    $name = getenv('DBNAME');

    $db = new PDO("mysql:host=$host;dbname=$name;", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    return $db;
};


$container[ReadUser::class] = function ($c) {
  return new ReadUser($c['pdo']);
};

$container[DeleteUser::class] = function ($c) {
    return new DeleteUser($c['pdo']);
};

$container[CreateUser::class] = function ($c) {
    return new CreateUser($c['pdo']);
};

$container[UpdateUser::class] = function ($c) {
    return new DeleteUser($c['pdo']);
};

$app->get('/user/{id}', ReadUser::class);
$app->delete('/user/{id}', DeleteUser::class);
$app->post('/user', CreateUser::class);
$app->put('/user/{id}', UpdateUser::class);



$app->run();
