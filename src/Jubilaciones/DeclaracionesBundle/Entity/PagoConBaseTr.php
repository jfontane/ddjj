<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBaseTr
 *
 * @ORM\Table(name="PagoConBaseTr")
 * @ORM\Entity()
 */
class PagoConBaseTr {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="13",max="13")
     */
    protected $identificacionRegistro='TRFACTURACION';

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="8",max="8")
     */
    protected $cantidadRegistro;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    * @Assert\Length(min="18",max="18")
     */
    protected $totalPrimerVencimiento;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    * @Assert\Length(min="18",max="18")
     */
    protected $totalSegundoVencimiento;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    * @Assert\Length(min="18",max="18")
     */
    protected $totalTercerVencimiento;

    /**
     * @ORM\ManyToOne(targetEntity="PagoConBase", inversedBy="pagosConBaseTr")
     * @ORM\JoinColumn(name="PagoConBase_id", referencedColumnName="id")
     */
    private $pagoConBaseTr;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set identificacionRegistro
     *
     * @param string $identificacionRegistro
     *
     * @return PagoConBaseTr
     */
    public function setIdentificacionRegistro($identificacionRegistro)
    {
        $this->identificacionRegistro = $identificacionRegistro;

        return $this;
    }

    /**
     * Get identificacionRegistro
     *
     * @return string
     */
    public function getIdentificacionRegistro()
    {
        return $this->identificacionRegistro;
    }

    /**
     * Set cantidadRegistro
     *
     * @param string $cantidadRegistro
     *
     * @return PagoConBaseTr
     */
    public function setCantidadRegistro($cantidadRegistro)
    {
        $this->cantidadRegistro = $cantidadRegistro;

        return $this;
    }

    /**
     * Get cantidadRegistro
     *
     * @return string
     */
    public function getCantidadRegistro()
    {
        return $this->cantidadRegistro;
    }

    /**
     * Set totalPrimerVencimiento
     *
     * @param float $totalPrimerVencimiento
     *
     * @return PagoConBaseTr
     */
    public function setTotalPrimerVencimiento($totalPrimerVencimiento)
    {
        $this->totalPrimerVencimiento = $totalPrimerVencimiento;

        return $this;
    }

    /**
     * Get totalPrimerVencimiento
     *
     * @return float
     */
    public function getTotalPrimerVencimiento()
    {
        return $this->totalPrimerVencimiento;
    }

    /**
     * Set totalSegundoVencimiento
     *
     * @param float $totalSegundoVencimiento
     *
     * @return PagoConBaseTr
     */
    public function setTotalSegundoVencimiento($totalSegundoVencimiento)
    {
        $this->totalSegundoVencimiento = $totalSegundoVencimiento;

        return $this;
    }

    /**
     * Get totalSegundoVencimiento
     *
     * @return float
     */
    public function getTotalSegundoVencimiento()
    {
        return $this->totalSegundoVencimiento;
    }

    /**
     * Set totalTercerVencimiento
     *
     * @param float $totalTercerVencimiento
     *
     * @return PagoConBaseTr
     */
    public function setTotalTercerVencimiento($totalTercerVencimiento)
    {
        $this->totalTercerVencimiento = $totalTercerVencimiento;

        return $this;
    }

    /**
     * Get totalTercerVencimiento
     *
     * @return float
     */
    public function getTotalTercerVencimiento()
    {
        return $this->totalTercerVencimiento;
    }

    /**
     * Set pagoConBaseTr
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBaseTr
     *
     * @return PagoConBaseTr
     */
    public function setPagoConBaseTr(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBaseTr = null)
    {
        $this->pagoConBaseTr = $pagoConBaseTr;

        return $this;
    }

    /**
     * Get pagoConBaseTr
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase
     */
    public function getPagoConBaseTr()
    {
        return $this->pagoConBaseTr;
    }
}
