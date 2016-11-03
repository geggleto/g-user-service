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

/**
 * Class ReadUser
 *
 * @package G\Services\User
 */
class ReadUser implements EndpointInterface
{
    /** @var \PDO */
    protected $db;

    /**
     * ReadUser constructor.
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
        $statement = $this->db->prepare("select * from `users` where id = ?");
        $statement->execute(array($args['id']));
        $result = $statement->fetch();
        return $response->withJson($result);
    }
}