<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Representante
 * 
 * @ORM\Table(name="representante")
 * @ORM\Entity(repositoryClass="Jubilaciones\DeclaracionesBundle\Repository\RepresentanteRepository")
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
    
    /**
     * @ORM\OneToMany(targetEntity="Organismo", mappedBy="representante")
     */
    protected $eventos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->eventos = new \Doctrine\Common\Collections\ArrayCollection();
    }

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
     * Set cuil
     *
     * @param string $cuil
     *
     * @return Representante
     */
    public function setCuil($cuil)
    {
        $this->cuil = $cuil;

        return $this;
    }

    /**
     * Get cuil
     *
     * @return string
     */
    public function getCuil()
    {
        return $this->cuil;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Representante
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
     * Set documentoTipo
     *
     * @param string $documentoTipo
     *
     * @return Representante
     */
    public function setDocumentoTipo($documentoTipo)
    {
        $this->documentoTipo = $documentoTipo;

        return $this;
    }

    /**
     * Get documentoTipo
     *
     * @return string
     */
    public function getDocumentoTipo()
    {
        return $this->documentoTipo;
    }

    /**
     * Set documentoNumero
     *
     * @param string $documentoNumero
     *
     * @return Representante
     */
    public function setDocumentoNumero($documentoNumero)
    {
        $this->documentoNumero = $documentoNumero;

        return $this;
    }

    /**
     * Get documentoNumero
     *
     * @return string
     */
    public function getDocumentoNumero()
    {
        return $this->documentoNumero;
    }

    /**
     * Set apellido
     *
     * @param string $apellido
     *
     * @return Representante
     */
    public function setApellido($apellido)
    {
        $this->apellido = $apellido;

        return $this;
    }

    /**
     * Get apellido
     *
     * @return string
     */
    public function getApellido()
    {
        return $this->apellido;
    }

    /**
     * Set nombres
     *
     * @param string $nombres
     *
     * @return Representante
     */
    public function setNombres($nombres)
    {
        $this->nombres = $nombres;

        return $this;
    }

    /**
     * Get nombres
     *
     * @return string
     */
    public function getNombres()
    {
        return $this->nombres;
    }

    /**
     * Set sexo
     *
     * @param string $sexo
     *
     * @return Representante
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Representante
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set fechaSolicitud
     *
     * @param \DateTime $fechaSolicitud
     *
     * @return Representante
     */
    public function setFechaSolicitud($fechaSolicitud)
    {
        $this->fechaSolicitud = $fechaSolicitud;

        return $this;
    }

    /**
     * Get fechaSolicitud
     *
     * @return \DateTime
     */
    public function getFechaSolicitud()
    {
        return $this->fechaSolicitud;
    }

    /**
     * Set fechaHabilitado
     *
     * @param \DateTime $fechaHabilitado
     *
     * @return Representante
     */
    public function setFechaHabilitado($fechaHabilitado)
    {
        $this->fechaHabilitado = $fechaHabilitado;

        return $this;
    }

    /**
     * Get fechaHabilitado
     *
     * @return \DateTime
     */
    public function getFechaHabilitado()
    {
        return $this->fechaHabilitado;
    }

    /**
     * Set fechaActualizacion
     *
     * @param \DateTime $fechaActualizacion
     *
     * @return Representante
     */
    public function setFechaActualizacion($fechaActualizacion)
    {
        $this->fechaActualizacion = $fechaActualizacion;

        return $this;
    }

    /**
     * Get fechaActualizacion
     *
     * @return \DateTime
     */
    public function getFechaActualizacion()
    {
        return $this->fechaActualizacion;
    }

    /**
     * Set login
     *
     * @param string $login
     *
     * @return Representante
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set habilitado
     *
     * @param string $habilitado
     *
     * @return Representante
     */
    public function setHabilitado($habilitado)
    {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return string
     */
    public function getHabilitado()
    {
        return $this->habilitado;
    }

    /**
     * Set entregoFormularios
     *
     * @param string $entregoFormularios
     *
     * @return Representante
     */
    public function setEntregoFormularios($entregoFormularios)
    {
        $this->entregoFormularios = $entregoFormularios;

        return $this;
    }

    /**
     * Get entregoFormularios
     *
     * @return string
     */
    public function getEntregoFormularios()
    {
        return $this->entregoFormularios;
    }

    /**
     * Set confirmoDatos
     *
     * @param string $confirmoDatos
     *
     * @return Representante
     */
    public function setConfirmoDatos($confirmoDatos)
    {
        $this->confirmoDatos = $confirmoDatos;

        return $this;
    }

    /**
     * Get confirmoDatos
     *
     * @return string
     */
    public function getConfirmoDatos()
    {
        return $this->confirmoDatos;
    }

    /**
     * Add evento
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Organismo $evento
     *
     * @return Representante
     */
    public function addEvento(\Jubilaciones\DeclaracionesBundle\Entity\Organismo $evento)
    {
        $this->eventos[] = $evento;

        return $this;
    }

    /**
     * Remove evento
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Organismo $evento
     */
    public function removeEvento(\Jubilaciones\DeclaracionesBundle\Entity\Organismo $evento)
    {
        $this->eventos->removeElement($evento);
    }

    /**
     * Get eventos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEventos()
    {
        return $this->eventos;
    }
}
