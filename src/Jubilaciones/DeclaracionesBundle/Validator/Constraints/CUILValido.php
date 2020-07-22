<?php

namespace Jubilaciones\DeclaracionesBundle\Validator\Constraints;

/**
 * Validacion para los CUIT
 *
 * Ver:
 *  - https://symfony.com/doc/3.4/validation/custom_constraint.html
 *
 * @author esangoi
 *
 * @Annotation
 *
 */
class CUILValido extends \Symfony\Component\Validator\Constraint{

    const LENGTH = 11;

}
