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
class CUILValidoValidator extends ConstraintValidator {

    public function validate($value, Constraint $constraint) {

        if (!$constraint instanceof CUILValido) {
            throw new UnexpectedTypeException($constraint, CUILValido::class);
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
                    ->buildViolation('El valor {{ value }} no es un numero de CUIT válido.')
                    ->setParameter('{{ value }}', $value)
                    ->addViolation();
            return;
        }

        $l = strlen($value);
        if($l !== $constraint::LENGTH){
            $this->context
                    ->buildViolation('El CUIT debe tener exactamente {{ limit }} digitos.')
                    ->setParameter('{{ limit }}', $constraint::LENGTH)
                    ->addViolation();
            return;
        }

    }

}
