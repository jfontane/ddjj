<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Organismo
 *
 * @ORM\Table(name="conveniocuentacorriente")
 * @ORM\Entity
 */
class Conveniocuentacorriente {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="10",max="13")
     */
    protected $codigoConvenio;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="10",max="10")
     */
    protected $codigoOrganismo;

    /**
     * @ORM\Column(type="string")
     */
    protected $tramo;

    /**
     * @ORM\Column(type="string")
     */
    protected $cuota;

    /**
     * @ORM\Column(type="string")
     */
    protected $tipoMovimiento;

    /**
     * @ORM\Column(type="date")
     */
    protected $fechaMovimiento;

    /**
     * @ORM\Column(type="date")
     */
    protected $fechaPago;

    /**
     * @ORM\Column(type="date")
     */
    protected $fechaVencimiento;

    /**
     * @ORM\Column(type="string")
     */
    protected $codigoMovimiento;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    protected $importe;

    /**
     * @ORM\Column(type="float", scale=2)
     */
    protected $saldo;

}
