<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBase
 *
 * @ORM\Table(name="PagoConBaseDatos")
 * @ORM\Entity()
 */
class PagoConBaseDatos {

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
    protected $identificadorDeuda;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="3",max="3")
     */
    protected $identificadorConcepto;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="19",max="19")
     */
    protected $identificadorUsuario;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="6",max="6")
     */
    protected $fechaPrimerVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="12",max="12")
     */
    protected $importePrimerVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="6",max="6")
     */
    protected $fechaSegundoVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="12",max="12")
     */
    protected $importeSegundoVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="6",max="6")
     */
    protected $fechaTercerVencimiento;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="12",max="12")
     */
    protected $importeTercerVencimiento;

    /**
     * @ORM\ManyToOne(targetEntity="PagoConBase", inversedBy="pagosConBaseDatos")
     * @ORM\JoinColumn(name="PagoConBase_id", referencedColumnName="id")
     */
    private $pagoConBaseDatos;



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
     * Set identificadorDeuda
     *
     * @param string $identificadorDeuda
     *
     * @return PagoConBaseDatos
     */
    public function setIdentificadorDeuda($identificadorDeuda)
    {
        $this->identificadorDeuda = $identificadorDeuda;

        return $this;
    }

    /**
     * Get identificadorDeuda
     *
     * @return string
     */
    public function getIdentificadorDeuda()
    {
        return $this->identificadorDeuda;
    }

    /**
     * Set identificadorConcepto
     *
     * @param string $identificadorConcepto
     *
     * @return PagoConBaseDatos
     */
    public function setIdentificadorConcepto($identificadorConcepto)
    {
        $this->identificadorConcepto = $identificadorConcepto;

        return $this;
    }

    /**
     * Get identificadorConcepto
     *
     * @return string
     */
    public function getIdentificadorConcepto()
    {
        return $this->identificadorConcepto;
    }

    /**
     * Set identificadorUsuario
     *
     * @param string $identificadorUsuario
     *
     * @return PagoConBaseDatos
     */
    public function setIdentificadorUsuario($identificadorUsuario)
    {
        $this->identificadorUsuario = $identificadorUsuario;

        return $this;
    }

    /**
     * Get identificadorUsuario
     *
     * @return string
     */
    public function getIdentificadorUsuario()
    {
        return $this->identificadorUsuario;
    }

    /**
     * Set fechaPrimerVencimiento
     *
     * @param string $fechaPrimerVencimiento
     *
     * @return PagoConBaseDatos
     */
    public function setFechaPrimerVencimiento($fechaPrimerVencimiento)
    {
        $this->fechaPrimerVencimiento = $fechaPrimerVencimiento;

        return $this;
    }

    /**
     * Get fechaPrimerVencimiento
     *
     * @return string
     */
    public function getFechaPrimerVencimiento()
    {
        return $this->fechaPrimerVencimiento;
    }

    /**
     * Set importePrimerVencimiento
     *
     * @param string $importePrimerVencimiento
     *
     * @return PagoConBaseDatos
     */
    public function setImportePrimerVencimiento($importePrimerVencimiento)
    {
        $this->importePrimerVencimiento = $importePrimerVencimiento;

        return $this;
    }

    /**
     * Get importePrimerVencimiento
     *
     * @return string
     */
    public function getImportePrimerVencimiento()
    {
        return $this->importePrimerVencimiento;
    }

    /**
     * Set fechaSegundoVencimiento
     *
     * @param string $fechaSegundoVencimiento
     *
     * @return PagoConBaseDatos
     */
    public function setFechaSegundoVencimiento($fechaSegundoVencimiento)
    {
        $this->fechaSegundoVencimiento = $fechaSegundoVencimiento;

        return $this;
    }

    /**
     * Get fechaSegundoVencimiento
     *
     * @return string
     */
    public function getFechaSegundoVencimiento()
    {
        return $this->fechaSegundoVencimiento;
    }

    /**
     * Set importeSegundoVencimiento
     *
     * @param string $importeSegundoVencimiento
     *
     * @return PagoConBaseDatos
     */
    public function setImporteSegundoVencimiento($importeSegundoVencimiento)
    {
        $this->importeSegundoVencimiento = $importeSegundoVencimiento;

        return $this;
    }

    /**
     * Get importeSegundoVencimiento
     *
     * @return string
     */
    public function getImporteSegundoVencimiento()
    {
        return $this->importeSegundoVencimiento;
    }

    /**
     * Set fechaTercerVencimiento
     *
     * @param string $fechaTercerVencimiento
     *
     * @return PagoConBaseDatos
     */
    public function setFechaTercerVencimiento($fechaTercerVencimiento)
    {
        $this->fechaTercerVencimiento = $fechaTercerVencimiento;

        return $this;
    }

    /**
     * Get fechaTercerVencimiento
     *
     * @return string
     */
    public function getFechaTercerVencimiento()
    {
        return $this->fechaTercerVencimiento;
    }

    /**
     * Set importeTercerVencimiento
     *
     * @param string $importeTercerVencimiento
     *
     * @return PagoConBaseDatos
     */
    public function setImporteTercerVencimiento($importeTercerVencimiento)
    {
        $this->importeTercerVencimiento = $importeTercerVencimiento;

        return $this;
    }

    /**
     * Get importeTercerVencimiento
     *
     * @return string
     */
    public function getImporteTercerVencimiento()
    {
        return $this->importeTercerVencimiento;
    }

    /**
     * Set pagoConBaseDatos
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBaseDatos
     *
     * @return PagoConBaseDatos
     */
    public function setPagoConBaseDatos(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBaseDatos = null)
    {
        $this->pagoConBaseDatos = $pagoConBaseDatos;

        return $this;
    }

    /**
     * Get pagoConBaseDatos
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase
     */
    public function getPagoConBaseDatos()
    {
        return $this->pagoConBaseDatos;
    }
}
