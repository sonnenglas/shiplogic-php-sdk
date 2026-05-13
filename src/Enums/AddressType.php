<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Enums;

enum AddressType: string
{
    case Residential = 'residential';
    case Business = 'business';
    case Counter = 'counter';
    case Locker = 'locker';
    case Unknown = 'unknown';
}
