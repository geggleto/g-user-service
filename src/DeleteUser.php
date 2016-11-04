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
 * Class DeleteUser
 *
 * @package G\Services\User
 */
class DeleteUser implements EndpointInterface
{
    /** @var \PDO */
    protected $db;

    /**
     * DeleteUser constructor.
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
        $statement = $this->db->prepare("delete from `users` where id = ?");
        $statement->execute(array($args['id']));
        return $response->withJson(array("message" => $statement->rowCount()." rows affected"));
    }
}