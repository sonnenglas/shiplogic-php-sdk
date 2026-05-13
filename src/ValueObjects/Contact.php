<?php

declare(strict_types=1);

namespace Sonnenglas\Shiplogic\ValueObjects;

use Sonnenglas\Shiplogic\Exceptions\InvalidArgumentException;

class Contact
{
    public function __construct(
        public readonly string $name,
        public readonly string $mobileNumber = '',
        public readonly string $email = '',
    ) {
        if ($this->name === '') {
            throw new InvalidArgumentException('Contact name must not be empty');
        }

        if ($this->mobileNumber === '' && $this->email === '') {
            throw new InvalidArgumentException('Contact must have at least one of mobile_number or email');
        }
    }

    /**
     * @return array<string, string>
     */
    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'mobile_number' => $this->mobileNumber,
            'email' => $this->email,
        ];
    }
}
