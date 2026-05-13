<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ValueObjects;

use Sonnenglas\Shiplogic\Exceptions\InvalidArgumentException;

class Parcel
{
    public function __construct(
        public readonly float $lengthCm,
        public readonly float $widthCm,
        public readonly float $heightCm,
        public readonly float $weightKg,
        public readonly ?string $parcelDescription = null,
        public readonly ?string $alternativeTrackingReference = null,
    ) {
        foreach (['lengthCm' => $lengthCm, 'widthCm' => $widthCm, 'heightCm' => $heightCm, 'weightKg' => $weightKg] as $name => $value) {
            if ($value <= 0) {
                throw new InvalidArgumentException("Parcel {$name} must be greater than zero");
            }
        }
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $payload = [
            'submitted_length_cm' => $this->lengthCm,
            'submitted_width_cm' => $this->widthCm,
            'submitted_height_cm' => $this->heightCm,
            'submitted_weight_kg' => $this->weightKg,
        ];

        if ($this->parcelDescription !== null) {
            $payload['parcel_description'] = $this->parcelDescription;
        }

        if ($this->alternativeTrackingReference !== null) {
            $payload['alternative_tracking_reference'] = $this->alternativeTrackingReference;
        }

        return $payload;
    }
}
