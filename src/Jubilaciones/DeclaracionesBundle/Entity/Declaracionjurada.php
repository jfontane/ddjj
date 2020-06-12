<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Declaracionjurada
 *
 * @ORM\Table(name="declaracionjurada", uniqueConstraints={
 *      @ORM\UniqueConstraint(columns={"organismo_id","periodo_anio","periodo_mes","tipo_liquidacion"})
 * }, indexes={
 *      @ORM\Index(columns={"organismo_id","periodo_anio","periodo_mes","tipo_liquidacion"})
 *            }
 * )
 * @ORM\Entity(repositoryClass="Jubilaciones\DeclaracionesBundle\Repository\DeclaracionjuradaRepository")
 */
class Declaracionjurada {

    const ESTADO_PENDIENTE = 1;
    const ESTADO_PROCESANDO = 2;
    const ESTADO_APROBADA = 3;
    const ESTADO_RECHAZADA = 4;
    
    const TIPO_LIQ_ORIG_NORMAL = '111';
    const TIPO_LIQ_CORRECTIVA_NORMAL = '112';
    const TIPO_LIQ_ORIG_SAC = '211';
    const TIPO_LIQ_CORRECTIVA_SAC = '212';
    const TIPO_LIQ_COMP_1 = '301';
    const TIPO_LIQ_COMP_2 = '302';
    const TIPO_LIQ_COMP_3 = '303';
    const TIPO_LIQ_COMP_4 = '304';


    public static function getEstados() {
        return array(
            self::ESTADO_PENDIENTE => 'Pendiente',
            self::ESTADO_PROCESANDO => 'Procesando',
            self::ESTADO_APROBADA => 'Aprobada',
            self::ESTADO_RECHAZADA => 'Rechazada'
        );
    }


    public static function getNomEstado($cod) {
        $estados = self::getEstados();
        if (isset($estados[$cod])) {
            return $estados[$cod];
        }
        return false;
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="4",max="4")
     */
    protected $periodoAnio;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="2",max="2")
     */
    protected $periodoMes;

    /**
     * @ORM\Column(type="string")
     */
    protected $tipoLiquidacion;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $fechaEntrega;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @Assert\Date()
     */
    protected $fechaIngreso;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $comentarios;

    /**
     * @ORM\Column(type="string", columnDefinition="enum('Pendiente','Procesando', 'Aprobada', 'Rechazada')")
     */
    protected $estado;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $jubidat;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $jubi1ind;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $importePersonal;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $importePatronal;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $importeNoRemunerativo;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $importeRemunerativo;

    /**
     * @ORM\Column(type="float", scale=2, nullable=true)
     */
    protected $importeOtros;

    /**
     * @ORM\ManyToOne(targetEntity="Organismo", inversedBy="declaracionesjuradas")
     * @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     */
    protected $organismo;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Set periodoAnio
     *
     * @param string $periodoAnio
     *
     * @return Declaracionjurada
     */
    public function setPeriodoAnio($periodoAnio) {
        $this->periodoAnio = $periodoAnio;

        return $this;
    }

    /**
     * Get periodoAnio
     *
     * @return string
     */
    public function getPeriodoAnio() {
        return $this->periodoAnio;
    }

    /**
     * Set periodoMes
     *
     * @param string $periodoMes
     *
     * @return Declaracionjurada
     */
    public function setPeriodoMes($periodoMes) {
        $this->periodoMes = $periodoMes;

        return $this;
    }

    /**
     * Get periodoMes
     *
     * @return string
     */
    public function getPeriodoMes() {
        return $this->periodoMes;
    }

    /**
     * Set tipoLiquidacion
     *
     * @param string $tipoLiquidacion
     *
     * @return Declaracionjurada
     */
    public function setTipoLiquidacion($tipoLiquidacion) {
        $this->tipoLiquidacion = $tipoLiquidacion;

        return $this;
    }

    /**
     * Get tipoLiquidacion
     *
     * @return string
     */
    public function getTipoLiquidacion() {
        return $this->tipoLiquidacion;
    }

    /**
     * Set fechaEntrega
     *
     * @param \DateTime $fechaEntrega
     *
     * @return Declaracionjurada
     */
    public function setFechaEntrega($fechaEntrega) {
        $this->fechaEntrega = $fechaEntrega;

        return $this;
    }

    /**
     * Get fechaEntrega
     *
     * @return \DateTime
     */
    public function getFechaEntrega() {
        return $this->fechaEntrega;
    }

    /**
     * Set fechaIngreso
     *
     * @param \DateTime $fechaIngreso
     *
     * @return Declaracionjurada
     */
    public function setFechaIngreso($fechaIngreso) {
        $this->fechaIngreso = $fechaIngreso;

        return $this;
    }

    /**
     * Get fechaIngreso
     *
     * @return \DateTime
     */
    public function getFechaIngreso() {
        return $this->fechaIngreso;
    }

    /**
     * Set comentarios
     *
     * @param string $comentarios
     *
     * @return Declaracionjurada
     */
    public function setComentarios($comentarios) {
        $this->comentarios = $comentarios;

        return $this;
    }

    /**
     * Get comentarios
     *
     * @return string
     */
    public function getComentarios() {
        return $this->comentarios;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Declaracionjurada
     */
    public function setEstado($estado) {
        $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado() {
        return $this->estado;
    }

    /**
     * Set jubidat
     *
     * @param string $jubidat
     *
     * @return Declaracionjurada
     */
    public function setJubidat($jubidat) {
        $this->jubidat = $jubidat;

        return $this;
    }

    /**
     * Get jubidat
     *
     * @return string
     */
    public function getJubidat() {
        return $this->jubidat;
    }

    /**
     * Set jubi1ind
     *
     * @param string $jubi1ind
     *
     * @return Declaracionjurada
     */
    public function setJubi1ind($jubi1ind) {
        $this->jubi1ind = $jubi1ind;

        return $this;
    }

    /**
     * Get jubi1ind
     *
     * @return string
     */
    public function getJubi1ind() {
        return $this->jubi1ind;
    }

    /**
     * Set organismo
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo
     *
     * @return Declaracionjurada
     */
    public function setOrganismo(\Jubilaciones\DeclaracionesBundle\Entity\Organismo $organismo = null) {
        $this->organismo = $organismo;

        return $this;
    }

    /**
     * Get organismo
     *
     * @return \Jubilaciones\DeclaracionesBundle\Entity\Organismo
     */
    public function getOrganismo() {
        return $this->organismo;
    }

    /**
     * Set importeOtros
     *
     * @param float $importeOtros
     *
     * @return Declaracionjurada
     */
    public function setImporteOtros($importeOtros) {
        $this->importeOtros = $importeOtros;

        return $this;
    }

    /**
     * Get importeOtros
     *
     * @return float
     */
    public function getImporteOtros() {
        return $this->importeOtros;
    }

    /**
     * Set importePersonal
     *
     * @param float $importePersonal
     *
     * @return Declaracionjurada
     */
    public function setImportePersonal($importePersonal) {
        $this->importePersonal = $importePersonal;

        return $this;
    }

    /**
     * Get importePersonal
     *
     * @return float
     */
    public function getImportePersonal() {
        return $this->importePersonal;
    }

    /**
     * Set importePatronal
     *
     * @param float $importePatronal
     *
     * @return Declaracionjurada
     */
    public function setImportePatronal($importePatronal) {
        $this->importePatronal = $importePatronal;

        return $this;
    }

    /**
     * Get importePatronal
     *
     * @return float
     */
    public function getImportePatronal() {
        return $this->importePatronal;
    }

    /**
     * Set importeNoRemunerativo
     *
     * @param float $importeNoRemunerativo
     *
     * @return Declaracionjurada
     */
    public function setImporteNoRemunerativo($importeNoRemunerativo) {
        $this->importeNoRemunerativo = $importeNoRemunerativo;

        return $this;
    }

    /**
     * Get importeNoRemunerativo
     *
     * @return float
     */
    public function getImporteNoRemunerativo() {
        return $this->importeNoRemunerativo;
    }

    /**
     * Set importeRemunerativo
     *
     * @param float $importeRemunerativo
     *
     * @return Declaracionjurada
     */
    public function setImporteRemunerativo($importeRemunerativo) {
        $this->importeRemunerativo = $importeRemunerativo;

        return $this;
    }

    /**
     * Get importeRemunerativo
     *
     * @return float
     */
    public function getImporteRemunerativo() {
        return $this->importeRemunerativo;
    }

}
