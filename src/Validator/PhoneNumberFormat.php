<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class PhoneNumberFormat extends Constraint
{
    public string $message = 'The phone format is not compatible with E.164';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    #[HasNamedArguments]
    public function __construct(string $mode, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->mode = $mode;
    }
}