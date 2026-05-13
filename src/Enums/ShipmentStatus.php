<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\Enums;

enum ShipmentStatus: string
{
    case Submitted = 'submitted';
    case CollectionAssigned = 'collection-assigned';
    case CollectionUnassigned = 'collection-unassigned';
    case CollectionRejected = 'collection-rejected';
    case CollectionException = 'collection-exception';
    case CollectionFailedAttempt = 'collection-failed-attempt';
    case Collected = 'collected';
    case AwaitingDropoff = 'awaiting-dropoff';
    case AtHub = 'at-hub';
    case OnHold = 'on-hold';
    case OnHoldInternal = 'on-hold-internal';
    case ReturnedToHub = 'returned-to-hub';
    case Manifested = 'manifested';
    case ReadyForDispatch = 'ready-for-dispatch';
    case InTransit = 'in-transit';
    case AtDestinationHub = 'at-destination-hub';
    case DeliveryAssigned = 'delivery-assigned';
    case DeliveryUnassigned = 'delivery-unassigned';
    case DeliveryRejected = 'delivery-rejected';
    case OutForDelivery = 'out-for-delivery';
    case DeliveryException = 'delivery-exception';
    case DeliveryFailedAttempt = 'delivery-failed-attempt';
    case ReadyForPickup = 'ready-for-pickup';
    case Delivered = 'delivered';
    case ReturnedToSender = 'returned-to-sender';
    case Undeliverable = 'undeliverable';
    case Cancelled = 'cancelled';
    case FloorCheck = 'floor-check';
    case CollectedFromLocker = 'collected-from-locker';
    case CollectedFromCounter = 'collected-from-counter';
    case InLocker = 'in-locker';
    case CollectAndReturnToHub = 'collect-and-return-to-hub';
    case SwadDimensions = 'swad-dimensions';
    case SwadImaging = 'swad-imaging';
}
