<?php

namespace App\Entity\Types;

class TicketStatus
{
    use \App\Entity\Traits\Enum;

    const OPEN        = 'OPEN';
    const IN_PROGRESS = 'IN_PROGRESS';
    const FINISHED    = 'FINISHED';
}