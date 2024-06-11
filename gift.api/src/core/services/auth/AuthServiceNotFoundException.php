<?php

namespace gift\api\core\services\auth;

use Exception;
use Throwable;

class AuthServiceNotFoundException extends Exception
{
    // Propriétés spécifiques si nécessaires

    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString():string
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
