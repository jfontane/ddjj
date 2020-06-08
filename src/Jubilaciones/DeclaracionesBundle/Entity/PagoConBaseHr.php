<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBaseHr
 *
 * @ORM\Table(name="PagoConBaseHr")
 * @ORM\Entity()
 */
class PagoConBaseHr {

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
    protected $identificacionRegistro='HRFACTURACION';

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="3",max="3")
     */
    protected $codigoEnte;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="6",max="6")
     */
    protected $fechaProceso;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="5",max="5")
     */
    protected $lote;

    /**
     * @ORM\ManyToOne(targetEntity="PagoConBase", inversedBy="pagosConBaseHr")
     * @ORM\JoinColumn(name="PagoConBase_id", referencedColumnName="id")
     */
    private $pagoConBaseHr;


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
     * @return PagoConBaseHr
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
     * Set codigoEnte
     *
     * @param string $codigoEnte
     *
     * @return PagoConBaseHr
     */
    public function setCodigoEnte($codigoEnte)
    {
        $this->codigoEnte = $codigoEnte;

        return $this;
    }

    /**
     * Get codigoEnte
     *
     * @return string
     */
    public function getCodigoEnte()
    {
        return $this->codigoEnte;
    }

    /**
     * Set fechaProceso
     *
     * @param string $fechaProceso
     *
     * @return PagoConBaseHr
     */
    public function setFechaProceso($fechaProceso)
    {
        $this->fechaProceso = $fechaProceso;

        return $this;
    }

    /**
     * Get fechaProceso
     *
     * @return string
     */
    public function getFechaProceso()
    {
        return $this->fechaProceso;
    }

    /**
     * Set lote
     *
     * @param string $lote
     *
     * @return PagoConBaseHr
     */
    public function setLote($lote)
    {
        $this->lote = $lote;

        return $this;
    }

    /**
     * Get lote
     *
     * @return string
     */
    public function getLote()
    {
        return $this->lote;
    }

    /**
     * Set pagoConBase
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBase
     *
     * @return PagoConBaseHr
     */
    public function setPagoConBase(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBase = null)
    {
        $this->pagoConBase = $pagoConBase;

        return $this;
    }

    /**
     * Get pagoConBase
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase
     */
    public function getPagoConBase()
    {
        return $this->pagoConBase;
    }

    /**
     * Set pagoConBaseHr
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBaseHr
     *
     * @return PagoConBaseHr
     */
    public function setPagoConBaseHr(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBase $pagoConBaseHr = null)
    {
        $this->pagoConBaseHr = $pagoConBaseHr;

        return $this;
    }

    /**
     * Get pagoConBaseHr
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\PagoConBase
     */
    public function getPagoConBaseHr()
    {
        return $this->pagoConBaseHr;
    }
}
