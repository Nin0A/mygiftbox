<?php

namespace gift\api\core\services\catalogue;

use Exception;
use Throwable;

class CatalogueServiceInvalidDataException extends Exception
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
