<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 2:15 PM
 */

namespace G\Services\User;


use G\Core\Http\EndpointInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class CreateUser
 *
 * @package G\Services\User
 */
class CreateUser implements EndpointInterface
{
    /** @var \PDO */
    protected $db;
    /**
     * CreateUser constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
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

                $statement = $this->db->prepare('insert into `users` set name = ?, username = ?, password = ?, email = ?');

                $obj = array(
                    $body['name'],
                    $body['username'],
                    $hashedPassword,
                    $body['email']
                );

                if ($statement->execute($obj)) {

                    return $response->withJson(array(
                        "id" => $this->db->lastInsertId(),
                        "name" => $body['name'],
                        "username" => $body['username'],
                        "password" => $hashedPassword,
                        "email" => $body['email']
                    ));

                } else {
                    return $response->withJson(array("message" => "User already exists"), 400);
                }

            } catch (\Exception $e) {
                return $response->withStatus(500)->write($e->getMessage());
            }

        } else {
            return $response->withJson($userValidator->getErrors(), 400);
        }
    }
}