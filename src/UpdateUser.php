<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-02
 * Time: 2:15 PM
 */

namespace G\Services\User;


use G\Core\Db\UpdateBuilder;
use G\Core\Http\IOObjectEndpoint;
use G\Core\Services\ValidatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class UpdateUser extends IOObjectEndpoint
{
    /** @var string  */
    protected $table;

    /**
     * Update constructor.
     *
     * @param string $table
     * @param UpdateBuilder $builder
     * @param ValidatorInterface $validator
     */
    public function __construct($table = "", UpdateBuilder $builder, ValidatorInterface $validator)
    {
        parent::__construct($builder, $validator);

        $this->table = $table;
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
        $this->response = $response;

        return $this->updateObject(
            $args['id'],
            array("password" => function ($password) { return password_hash($password, PASSWORD_BCRYPT); }), //Add mutators
            $this->table, //where are we putting the info - what table?
            $request->getParsedBody() //What data are we persisting
        );
    }
}