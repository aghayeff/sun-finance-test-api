<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class PhoneNumberFormatValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();

        try {
            $swissNumberProto = $phoneUtil->parse($value);

            if (!$phoneUtil->isValidNumber($swissNumberProto)) {
                $this->context->buildViolation($constraint->message)
                    ->setParameter('{{ string }}', $value)
                    ->addViolation();
            }

        } catch (\libphonenumber\NumberParseException $e) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }
}