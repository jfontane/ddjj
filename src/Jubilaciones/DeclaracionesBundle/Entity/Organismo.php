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
    protected $nombre;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $domicilioCalle;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $domicilioNumero;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $localidad;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $codigoPostal;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $departamento;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $telefonoCaracteristica;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $telefonoNumero;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $cuit;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')", nullable=true)
     */
    protected $entregoFormulario;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')", nullable=true)
     */
    protected $habilitado;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Norte', 'Sur')", nullable=true)
     */
    protected $zona;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')", nullable=true)
     */
    protected $amparo;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Si', 'No')", nullable=true)
     */
    protected $pagaConBoleta;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $digitoVerificador;

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
     * @ORM\OneToOne(targetEntity="User", mappedBy="organismo")
     */
    protected $usuario;

    public function __toString() {
        return $this->getNombre() . '-' . $this->getCodigo();
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->declaracionesjuradas = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set domicilioCalle
     *
     * @param string $domicilioCalle
     *
     * @return Organismo
     */
    public function setDomicilioCalle($domicilioCalle) {
        $this->domicilioCalle = $domicilioCalle;

        return $this;
    }

    /**
     * Get domicilioCalle
     *
     * @return string
     */
    public function getDomicilioCalle() {
        return $this->domicilioCalle;
    }

    /**
     * Set domicilioNumero
     *
     * @param string $domicilioNumero
     *
     * @return Organismo
     */
    public function setDomicilioNumero($domicilioNumero) {
        $this->domicilioNumero = $domicilioNumero;

        return $this;
    }

    /**
     * Get domicilioNumero
     *
     * @return string
     */
    public function getDomicilioNumero() {
        return $this->domicilioNumero;
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
     * Set telefonoCaracteristica
     *
     * @param string $telefonoCaracteristica
     *
     * @return Organismo
     */
    public function setTelefonoCaracteristica($telefonoCaracteristica) {
        $this->telefonoCaracteristica = $telefonoCaracteristica;

        return $this;
    }

    /**
     * Get telefonoCaracteristica
     *
     * @return string
     */
    public function getTelefonoCaracteristica() {
        return $this->telefonoCaracteristica;
    }

    /**
     * Set telefonoNumero
     *
     * @param string $telefonoNumero
     *
     * @return Organismo
     */
    public function setTelefonoNumero($telefonoNumero) {
        $this->telefonoNumero = $telefonoNumero;

        return $this;
    }

    /**
     * Get telefonoNumero
     *
     * @return string
     */
    public function getTelefonoNumero() {
        return $this->telefonoNumero;
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
     * Set habilitado
     *
     * @param string $habilitado
     *
     * @return Organismo
     */
    public function setHabilitado($habilitado) {
        $this->habilitado = $habilitado;

        return $this;
    }

    /**
     * Get habilitado
     *
     * @return string
     */
    public function getHabilitado() {
        return $this->habilitado;
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

    /**
     * Set usuario
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\User $usuario
     *
     * @return Organismo
     */
    public function setUsuario(\Jubilaciones\DeclaracionesBundle\Entity\User $usuario = null) {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\User
     */
    public function getUsuario() {
        return $this->usuario;
    }

    /**
     * Add convenioscuota
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Conveniocuota $convenioscuota
     *
     * @return Organismo
     */
    public function addConvenioscuota(\Jubilaciones\DeclaracionesBundle\Entity\Conveniocuota $convenioscuota) {
        $this->convenioscuotas[] = $convenioscuota;

        return $this;
    }

    /**
     * Remove convenioscuota
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Conveniocuota $convenioscuota
     */
    public function removeConvenioscuota(\Jubilaciones\DeclaracionesBundle\Entity\Conveniocuota $convenioscuota) {
        $this->convenioscuotas->removeElement($convenioscuota);
    }

    /**
     * Get convenioscuotas
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getConvenioscuotas() {
        return $this->convenioscuotas;
    }

}
