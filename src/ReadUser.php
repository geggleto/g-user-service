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

class ReadUser implements EndpointInterface
{

    public function __invoke(Request $request, Response $response, array $args)
    {
        // TODO: Implement __invoke() method.
    }
}