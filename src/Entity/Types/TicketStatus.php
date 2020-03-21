<?php

namespace App\Entity\Types;

class UserType
{
    use \App\Entity\Traits\Enum;

    const BUYER    = 'BUYER';
    const SUPPLIER = 'SUPPLIER';
}