<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jubilaciones\DeclaracionesBundle\Entity
 * 
 * @ORM\Table(name="organismo")
 */
class Organismo {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="10",max="10")
     */
    protected $codigo;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombre;

    /**
     * @ORM\Column(type="string")
     */
    protected $domicilio;

    /**
     * @ORM\Column(type="string")
     */
    protected $localidad;

    /**
     * @ORM\Column(type="string")
     */
    protected $codigoPostal;

    /**
     * @ORM\Column(type="string")
     */
    protected $departamento;

    /**
     * @ORM\Column(type="string")
     */
    protected $caracteristica;

    /**
     * @ORM\Column(type="string")
     */
    protected $telefono;

    /**
     * @ORM\Column(type="string")
     */
    protected $cuit;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="string")
     * @Assert\Choice({"Si","No"})
     */
    protected $amparo;

    /**
     * @ORM\Column(type="string")
     */
    protected $zona;

    /**
     * @ORM\ManyToOne(targetEntity="Representante", inversedBy="eventos")
     * @ORM\JoinColumn(name="representante_id", referencedColumnName="id")
     */
    protected $representante;
    

}
