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
use Valitron\Validator;

/**
 * Class CreateUser
 *
 * @package G\Services\User
 */
class CreateUser implements EndpointInterface
{
    /** @var \PDO */
    protected $db;

    protected $errors;

    /**
     * CreateUser constructor.
     * @param \PDO $pdo
     */
    public function __construct(\PDO $pdo)
    {
        $this->db = $pdo;
        $this->errors = array();
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
        if ($this->validate($body)) {
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
                    return $response->withJson($obj);
                } else {
                    return $response->withJson(array("message" => "User already exists"), 400);
                }

            } catch (\Exception $e) {
                return $response->withStatus(500);
            }

        } else {
            return $response->withJson($this->errors, 400);
        }
    }

    /**
     * @param array $body
     * @return bool
     */
    public function validate(array $body) {
        $validator = new Validator($body);
        $validator->rule('required', array('username','password', 'email', 'name'));
        $validator->rule('email', 'email');
        $validator->rule('lengthBetween', array('username', 'password'), 6, 64);

        $this->errors = $validator->errors();

        return $validator->validate();
    }
}