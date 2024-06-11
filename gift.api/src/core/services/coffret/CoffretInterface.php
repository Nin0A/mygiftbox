<?php

namespace gift\api\core\services\coffret;

interface CoffretInterface
{
    /**
     * 
     */
    public function getBoxes(): array;
    public function createBox(array $values);
    
}
