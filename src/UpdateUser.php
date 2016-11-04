<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 2:15 PM
 */

namespace G\Services\User;


use G\Core\Db\UpdateBuilder;
use G\Core\Http\EndpointInterface;
use G\Services\User\Validators\UserValidator;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UpdateUser implements EndpointInterface
{
    /** @var UpdateBuilder */
    protected $builder;

    /**
     * UpdateUser constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(UpdateBuilder $builder)
    {
        $this->builder = $builder;
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @param array $args
     *
     * @return ResponseInterface
     */
    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, array $args)
    {
        /** @var $response \Slim\Http\Response */
        $body = $request->getParsedBody();
        $userValidator = new UserValidator($body);

        if ($userValidator->validate()) {
            try {
                $hashedPassword = password_hash($body['password'], PASSWORD_BCRYPT);

                if ($this->builder
                    ->setTable('users')
                    ->addColumn('name', $body['name'])
                    ->addColumn('username', $body['username'])
                    ->addColumn('password', $hashedPassword)
                    ->addColumn('email', $body['email'])
                    ->setId($args['id'])
                    ->execute()) {

                    return $response->withJson(array(
                        "id" => $args['id'],
                        "name" => $body['name'],
                        "username" => $body['username'],
                        "password" => $hashedPassword,
                        "email" => $body['email']
                    ));

                } else {
                    return $response->withJson(array("message" => "User already exists"), 400);
                }

            } catch (\Exception $e) {
                return $response->withJson(array("message" => $e->getMessage()), 500);
            }

        } else {
            return $response->withJson($userValidator->getErrors(), 400);
        }
    }
}