<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jubilaciones\DeclaracionesBundle\Entity
 * 
 * @ORM\Table(name="representante")
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
     * @ORM\ManyToOne(targetEntity="Organismo")
     * @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     */
    protected $organismo;
    

}
