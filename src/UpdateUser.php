<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 2:15 PM
 */

namespace G\Services\User;


use G\Core\Http\EndpointInterface;
use Slim\Http\Request;
use Slim\Http\Response;

class UpdateUser implements EndpointInterface
{
    /** @var \PDO */
    protected $db;

    /**
     * UpdateUser constructor.
     *
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     *
     * @return Response
     */
    public function __invoke(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();
        $userValidator = new UserValidator($body);

        if ($userValidator->validate()) {
            try {
                $hashedPassword = password_hash($body['password'], PASSWORD_BCRYPT);

                $statement = $this->db->prepare('update `users` set name = ?, username = ?, password = ?, email = ? where id = ?');

                $obj = array(
                    $body['name'],
                    $body['username'],
                    $hashedPassword,
                    $body['email'],
                    $args['id']
                );

                if ($statement->execute($obj)) {
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
                return $response->withStatus(500);
            }

        } else {
            return $response->withJson($userValidator->getErrors(), 400);
        }
    }
}