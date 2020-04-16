<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Jubilaciones\DeclaracionesBundle\Entity
 * 
 * @ORM\Table(name="representante")
 */
class Representante {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="11",max="11")
     */
    protected $cuil;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    protected $fechaIngreso;

    /**
     * @ORM\Column(type="string")
     */
    protected $documentoTipo;

    /**
     * @ORM\Column(type="string")
     */
    protected $documentoNumero;

    /**
     * @ORM\Column(type="string")
     */
    protected $apellido;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombres;

    /**
     * @ORM\Column(type="string")
     */
    protected $sexo;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    protected $fechaSolicitud;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    protected $fechaHabilitado;

    /**
     * @ORM\Column(type="date")
     * @Assert\Date()
     */
    protected $fechaActualizacion;

    /**
     * @ORM\Column(type="string")
     */
    protected $login;

    /**
     * @ORM\Column(type="string")
     */
    protected $habilitado;

    /**
     * @ORM\Column(type="string")
     */
    protected $entregoFormularios;

    /**
     * @ORM\Column(type="string")
     */
    protected $confirmoDatos;

}
