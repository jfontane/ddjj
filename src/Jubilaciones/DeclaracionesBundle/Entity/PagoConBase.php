<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBase
 *
 * @ORM\Table(name="pagoconbase")
 * @ORM\Entity()
 */
class Pagoconbase {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $periodoAnio;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $periodoMes;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $tipoLiquidacion;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank()
     */
    protected $dia;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="8",max="8")
     */
    protected $nombreArchivoRefresh;

    /**
     * @ORM\OneToMany(targetEntity="Declaracionjurada", mappedBy="pagoconbase")
     */
    protected $declaracionesjuradas;

}
