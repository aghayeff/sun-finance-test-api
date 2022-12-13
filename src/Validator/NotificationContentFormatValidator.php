<?php

namespace App\Validator;

use App\Config\NotificationChannel;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NotificationContentFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        $channel = $this->context->getRoot()->getChannel();

        if ($channel == NotificationChannel::Sms->value && strlen($value) > 140) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}