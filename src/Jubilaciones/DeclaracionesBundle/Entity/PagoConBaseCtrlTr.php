<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBaseCtrlTr
 *
 * @ORM\Table(name="PagoConBaseCtrlTr")
 * @ORM\Entity()
 */
class PagoConBaseCtrlTr {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;


    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="9",max="9")
     */
    protected $identificadorFin="FINAL";

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="8",max="8")
     */
    protected $cantidadTotalRegistros;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="18",max="18")
     */
    protected $importeTotalPrimerVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="18",max="18")
     */
    protected $importeTotalSegundoVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="18",max="18")
     */
    protected $importeTotalTercerVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="8",max="8")
     */
    protected $fechaUltimoVencimiento;

    /**
     * @ORM\ManyToOne(targetEntity="PagoConBaseCtrl", inversedBy="pagosConBaseCtrlTr")
     * @ORM\JoinColumn(name="PagoConBase_id", referencedColumnName="id")
     */
    private $pagoConBaseCtrlTr;

}
