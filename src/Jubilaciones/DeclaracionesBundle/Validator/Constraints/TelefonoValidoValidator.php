<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Jubilaciones\DeclaracionesBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

/**
 * Description of TelefonoValidoValidator
 *
 * @author esangoi
 */
class TelefonoValidoValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {

        if (!$constraint instanceof TelefonoValido) {
            throw new UnexpectedTypeException($constraint, TelefonoValido::class);
        }

        // custom constraints should ignore null and empty values to allow
        // other constraints (NotBlank, NotNull, etc.) take care of that
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedTypeException($value, 'string');
        }

        if(!ctype_digit($value)){
            $this->context
                    ->buildViolation('El numero de telefóno solo debe contener números.')
                    ->setParameter('{{ limit }}', $value)
                    ->addViolation();
            return;
        }

        $l = strlen($value);
        if($l < $constraint::MIN_LENGTH){
            $this->context
                    ->buildViolation('El telefóno debe tener al menos {{ limit }} digitos.')
                    ->setParameter('{{ limit }}', $constraint::MIN_LENGTH)
                    ->addViolation();
            return;
        }

        if($l > $constraint::MAX_LENGTH){
            $this->context
                    ->buildViolation('El telefóno debe tener como máximo {{ limit }} digitos.')
                    ->setParameter('{{ limit }}', $constraint::MAX_LENGTH)
                    ->addViolation();
        }




    }

}
