<?php

namespace Jubilaciones\DeclaracionesBundle\Validator\Constraints;

/**
 * Validacion para los telefonos
 *
 * Ver:
 *  - https://symfony.com/doc/3.4/validation/custom_constraint.html
 *
 * @author esangoi
 *
 * @Annotation
 * 
 */

class TelefonoValido extends \Symfony\Component\Validator\Constraint{

    const MIN_LENGTH = 6;
    const MAX_LENGTH = 16;

}
