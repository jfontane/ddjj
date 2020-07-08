<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBaseCtrl
 *
 * @ORM\Table(name="PagoConBaseCtrlDatos")
 * @ORM\Entity()
 */
class PagoConBaseCtrlDatos {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="5",max="5")
     */
    protected $identificadorDatos="LOTES";

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="5",max="5")
     */
    protected $numeroLote;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="8",max="8")
     */
    protected $cantidadRegistrosLote;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="18",max="18")
     */
    protected $importePrimerVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="18",max="18")
     */
    protected $importeSegundoVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="18",max="18")
     */
    protected $importeTercerVencimiento;

    /**
     * @ORM\ManyToOne(targetEntity="PagoConBaseCtrl", inversedBy="pagosConBaseCtrlDatos")
     * @ORM\JoinColumn(name="PagoConBase_id", referencedColumnName="id")
     */
    private $pagoConBaseCtrlDatos;

}
