<?php

namespace App\Validation;

class ValidationResult
{
    public function __construct(
        private array $errors = [],
        private array $acceptedData = [],
    ) {
    }

    public function isValid(): bool
    {
        return $this->errors === [];
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function data(): array
    {
        return $this->acceptedData;
    }
}