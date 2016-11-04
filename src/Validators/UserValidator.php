<?php
/**
 * Created by PhpStorm.
 * User: Glenn
 * Date: 2016-11-03
 * Time: 10:10 AM
 */

namespace G\Services\User\Validators;


use G\Core\Services\ValidatorInterface;
use Valitron\Validator;

/**
 * Class UserValidator
 *
 * @package G\Services\User
 */
class UserValidator implements ValidatorInterface
{
    /** @var Validator  */
    protected $validator;

    /** @var array|bool */
    protected $errors;

    /**
     * UserValidator constructor.
     *
     */
    public function __construct()
    {
        $this->validator = null;
    }

    public function setData(array $data) {
        $this->validator = new Validator($data);
        $this->validator->rule('required', array('username','password', 'email', 'name'));
        $this->validator->rule('email', 'email');
        $this->validator->rule('lengthBetween', array('username', 'password'), 6, 64);

        $this->errors = $this->validator->errors();
    }

    public function validate() {

        return $this->validator->validate();
    }

    /**
     * @return array|bool
     */
    public function getErrors()
    {
        return $this->errors;
    }


}