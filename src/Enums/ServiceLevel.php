<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Enums;

enum ServiceLevel: string
{
    case Economy = 'ECO';
    case Overnight = 'OVN';
    case OvernightExpress = 'OVNX';
    case EconomyExpress = 'ECOX';
    case LockerToDoor = 'LSE';
    case CounterToCounter = 'CTC';
}
