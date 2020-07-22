<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Jubilaciones\DeclaracionesBundle\Validator\Constraints as BaseAssert;

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
     * @ORM\Column(type="string", length=11, unique=true)
     * @BaseAssert\CUILValido
     * @Assert\NotBlank(
     *    message="El numero de CUIL no puede quedar vacio."
     * )
     */
    protected $cuil;

    /**
     * @ORM\Column(type="string")
     */
    protected $apellido;

    /**
     * @ORM\Column(type="string")
     */
    protected $nombres;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('M', 'F')", nullable=true)
     */
    protected $sexo;

    /**
     * @ORM\Column(type="string")
     */
    protected $email;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $fechaActualizacion;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')")
     */
    protected $confirmoDatos;

    /**
     * @ORM\OneToMany(targetEntity="Organismo", mappedBy="representante")
     */
    protected $organismos;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->organismos = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add organismo
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo
     *
     * @return Representante
     */
    public function addOrganismo(\Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo)
    {
        $this->organismos[] = $organismo;

        return $this;
    }

    /**
     * Remove organismo
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo
     */
    public function removeOrganismo(\Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo)
    {
        $this->organismos->removeElement($organismo);
    }

    /**
     * Get organismos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getOrganismos()
    {
        return $this->organismos;
    }

    public function __toString() {
        return $this->getCuil().'-'.$this->getApellido() . ', ' . $this->getNombres();
    }



}
