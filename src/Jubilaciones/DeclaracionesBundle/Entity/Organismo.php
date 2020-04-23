<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Organismo
 * 
 * @ORM\Table(name="organismo")
 * @ORM\Entity(repositoryClass="Jubilaciones\DeclaracionesBundle\Repository\OrganismoRepository")
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
    protected $password;

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
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')")
     * @Assert\Choice({"Si","No"})
     */
    protected $amparo;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')")
     * @Assert\Choice({"Si","No"})
     */
    protected $entregoFormulario;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Norte', 'Sur')")
     */
    protected $zona;

    /**
     * @ORM\ManyToOne(targetEntity="Representante", inversedBy="organismos")
     * @ORM\JoinColumn(name="representante_id", referencedColumnName="id")
     */
    protected $representante;

    /**
     * @ORM\OneToMany(targetEntity="Declaracionjurada", mappedBy="organismo")
     */
    protected $declaracionesjuradas;

    /**
     * Constructor
     */
    public function __construct() {
        $this->declaracionesjuradas = new ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set codigo
     *
     * @param string $codigo
     *
     * @return Organismo
     */
    public function setCodigo($codigo) {
        $this->codigo = $codigo;

        return $this;
    }

    /**
     * Get codigo
     *
     * @return string
     */
    public function getCodigo() {
        return $this->codigo;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return Organismo
     */
    public function setPassword($password) {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword() {
        return $this->password;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Organismo
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * Set domicilio
     *
     * @param string $domicilio
     *
     * @return Organismo
     */
    public function setDomicilio($domicilio) {
        $this->domicilio = $domicilio;

        return $this;
    }

    /**
     * Get domicilio
     *
     * @return string
     */
    public function getDomicilio() {
        return $this->domicilio;
    }

    /**
     * Set localidad
     *
     * @param string $localidad
     *
     * @return Organismo
     */
    public function setLocalidad($localidad) {
        $this->localidad = $localidad;

        return $this;
    }

    /**
     * Get localidad
     *
     * @return string
     */
    public function getLocalidad() {
        return $this->localidad;
    }

    /**
     * Set codigoPostal
     *
     * @param string $codigoPostal
     *
     * @return Organismo
     */
    public function setCodigoPostal($codigoPostal) {
        $this->codigoPostal = $codigoPostal;

        return $this;
    }

    /**
     * Get codigoPostal
     *
     * @return string
     */
    public function getCodigoPostal() {
        return $this->codigoPostal;
    }

    /**
     * Set departamento
     *
     * @param string $departamento
     *
     * @return Organismo
     */
    public function setDepartamento($departamento) {
        $this->departamento = $departamento;

        return $this;
    }

    /**
     * Get departamento
     *
     * @return string
     */
    public function getDepartamento() {
        return $this->departamento;
    }

    /**
     * Set caracteristica
     *
     * @param string $caracteristica
     *
     * @return Organismo
     */
    public function setCaracteristica($caracteristica) {
        $this->caracteristica = $caracteristica;

        return $this;
    }

    /**
     * Get caracteristica
     *
     * @return string
     */
    public function getCaracteristica() {
        return $this->caracteristica;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     *
     * @return Organismo
     */
    public function setTelefono($telefono) {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string
     */
    public function getTelefono() {
        return $this->telefono;
    }

    /**
     * Set cuit
     *
     * @param string $cuit
     *
     * @return Organismo
     */
    public function setCuit($cuit) {
        $this->cuit = $cuit;

        return $this;
    }

    /**
     * Get cuit
     *
     * @return string
     */
    public function getCuit() {
        return $this->cuit;
    }

    /**
     * Set email
     *
     * @param string $email
     *
     * @return Organismo
     */
    public function setEmail($email) {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Set amparo
     *
     * @param string $amparo
     *
     * @return Organismo
     */
    public function setAmparo($amparo) {
        $this->amparo = $amparo;

        return $this;
    }

    /**
     * Get amparo
     *
     * @return string
     */
    public function getAmparo() {
        return $this->amparo;
    }

    /**
     * Set entregoFormulario
     *
     * @param string $entregoFormulario
     *
     * @return Organismo
     */
    public function setEntregoFormulario($entregoFormulario) {
        $this->entregoFormulario = $entregoFormulario;
        return $this;
    }

    /**
     * Get entregoFormulario
     *
     * @return string
     */
    public function getEntregoFormulario() {
        return $this->entregoFormulario;
    }

    /**
     * Set zona
     *
     * @param string $zona
     *
     * @return Organismo
     */
    public function setZona($zona) {
        $this->zona = $zona;

        return $this;
    }

    /**
     * Get zona
     *
     * @return string
     */
    public function getZona() {
        return $this->zona;
    }

    /**
     * Set representante
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Representante $representante
     *
     * @return Organismo
     */
    public function setRepresentante(\Jubilaciones\DeclaracionesBundle\Entity\Representante $representante = null) {
        $this->representante = $representante;

        return $this;
    }

    /**
     * Get representante
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\Representante
     */
    public function getRepresentante() {
        return $this->representante;
    }

    /**
     * Add declaracionesjurada
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada $declaracionesjurada
     *
     * @return Organismo
     */
    public function addDeclaracionesjurada(\Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada $declaracionesjurada) {
        $this->declaracionesjuradas[] = $declaracionesjurada;

        return $this;
    }

    /**
     * Remove declaracionesjurada
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada $declaracionesjurada
     */
    public function removeDeclaracionesjurada(\Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada $declaracionesjurada) {
        $this->declaracionesjuradas->removeElement($declaracionesjurada);
    }

    /**
     * Get declaracionesjuradas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getDeclaracionesjuradas() {
        return $this->declaracionesjuradas;
    }

}
