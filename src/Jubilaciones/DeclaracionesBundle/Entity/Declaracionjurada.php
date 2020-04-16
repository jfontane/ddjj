<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Declaracionjurada
 * 
 * @ORM\Table(name="declaracionjurada")
 * @ORM\Entity(repositoryClass="Jubilaciones\DeclaracionesBundle\Repository\DeclaracionjuradaRepository")
 */
class Declaracionjurada {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="6",max="6")
     */
    protected $periodo;

    /**
     * @ORM\Column(type="string")
     */
    protected $tipoLiquidacion;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    protected $fechaEntrega;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    protected $fechaIngreso;

    /**
     * @ORM\Column(type="string")
     */
    protected $encabezado;

    /**
     * @ORM\Column(type="string")
     */
    protected $comentarios;

    /**
     * @ORM\Column(type="string")
     */
    protected $estado;
    
    /**
     * @ORM\ManyToOne(targetEntity="Organismo", inversedBy="declaracionjuradas")
     * @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     */
    protected $organismo;
    


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
     * Set periodo
     *
     * @param string $periodo
     *
     * @return Declaracionjurada
     */
    public function setPeriodo($periodo)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo
     *
     * @return string
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set tipoLiquidacion
     *
     * @param string $tipoLiquidacion
     *
     * @return Declaracionjurada
     */
    public function setTipoLiquidacion($tipoLiquidacion)
    {
        $this->tipoLiquidacion = $tipoLiquidacion;

        return $this;
    }

    /**
     * Get tipoLiquidacion
     *
     * @return string
     */
    public function getTipoLiquidacion()
    {
        return $this->tipoLiquidacion;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return Declaracionjurada
     */
    public function setFechaEntrega($fechaEntrega)
    {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime
     */
    public function getFechaEntrega()
    {
        return $this->fechaEntrega;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Declaracionjurada
     */
    public function setFechaIngreso($fechaIngreso)
    {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso()
    {
        return $this->fechaIngreso;
    }

    /**
     * Set encabezado
     *
     * @param string $encabezado
     *
     * @return Declaracionjurada
     */
    public function setEncabezado($encabezado)
    {
        $this->encabezado = $encabezado;

        return $this;
    }

    /**
     * Get encabezado
     *
     * @return string
     */
    public function getEncabezado()
    {
        return $this->encabezado;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return Declaracionjurada
     */
    public function setComentarios($comentarios)
    {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios()
    {
        return $this->comentarios;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Declaracionjurada
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set organismo
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo
     *
     * @return Declaracionjurada
     */
    public function setOrganismo(\Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo = null)
    {
        $this->organismo = $organismo;

        return $this;
    }

    /**
     * Get organismo
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\Organismo
     */
    public function getOrganismo()
    {
        return $this->organismo;
    }
}
