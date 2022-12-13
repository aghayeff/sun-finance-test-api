<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

class NotificationContentFormat extends Constraint
{
    public string $message = 'This content length is too long!';
    // If the constraint has configuration options, define them as public properties
    public string $mode = 'strict';

    #[HasNamedArguments]
    public function __construct(string $mode, array $groups = null, mixed $payload = null)
    {
        parent::__construct([], $groups, $payload);
        $this->mode = $mode;
    }
}