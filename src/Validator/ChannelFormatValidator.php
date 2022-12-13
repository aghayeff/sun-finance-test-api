<?php

namespace App\Validator;

use App\Config\NotificationChannel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ChannelFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!in_array($value, NotificationChannel::values())) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}