<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBase
 *
 * @ORM\Table(name="PagoConBase")
 * @ORM\Entity()
 */
class PagoConBase {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="8",max="8")
     */
    protected $nombreArchivoRefresh;

    /**
     * @ORM\OneToMany(targetEntity="PagoConBaseHr", mappedBy="pagoConBaseHr")
     */
    private $pagosConBaseHr;

    /**
     * @ORM\OneToMany(targetEntity="PagoConBaseTr", mappedBy="pagoConBaseTr")
     */
    private $pagosConBaseTr;

    /**
     * @ORM\OneToMany(targetEntity="PagoConBaseDatos", mappedBy="pagoConBaseDatos")
     */
    private $pagosConBaseDatos;




    /**
     * Constructor
     */
    public function __construct()
    {
        $this->pagosConBaseHr = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set nombreArchivoRefresh
     *
     * @param string $nombreArchivoRefresh
     *
     * @return PagoConBase
     */
    public function setNombreArchivoRefresh($nombreArchivoRefresh)
    {
        $this->nombreArchivoRefresh = $nombreArchivoRefresh;

        return $this;
    }

    /**
     * Get nombreArchivoRefresh
     *
     * @return string
     */
    public function getNombreArchivoRefresh()
    {
        return $this->nombreArchivoRefresh;
    }

    /**
     * Add pagosConBaseHr
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseHr $pagosConBaseHr
     *
     * @return PagoConBase
     */
    public function addPagosConBaseHr(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseHr $pagosConBaseHr)
    {
        $this->pagosConBaseHr[] = $pagosConBaseHr;

        return $this;
    }

    /**
     * Remove pagosConBaseHr
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseHr $pagosConBaseHr
     */
    public function removePagosConBaseHr(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseHr $pagosConBaseHr)
    {
        $this->pagosConBaseHr->removeElement($pagosConBaseHr);
    }

    /**
     * Get pagosConBaseHr
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosConBaseHr()
    {
        return $this->pagosConBaseHr;
    }

    /**
     * Add pagosConBaseTr
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseTr $pagosConBaseTr
     *
     * @return PagoConBase
     */
    public function addPagosConBaseTr(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseTr $pagosConBaseTr)
    {
        $this->pagosConBaseTr[] = $pagosConBaseTr;

        return $this;
    }

    /**
     * Remove pagosConBaseTr
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseTr $pagosConBaseTr
     */
    public function removePagosConBaseTr(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseTr $pagosConBaseTr)
    {
        $this->pagosConBaseTr->removeElement($pagosConBaseTr);
    }

    /**
     * Get pagosConBaseTr
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosConBaseTr()
    {
        return $this->pagosConBaseTr;
    }

    /**
     * Add pagosConBaseDato
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseDatos $pagosConBaseDato
     *
     * @return PagoConBase
     */
    public function addPagosConBaseDato(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseDatos $pagosConBaseDato)
    {
        $this->pagosConBaseDatos[] = $pagosConBaseDato;

        return $this;
    }

    /**
     * Remove pagosConBaseDato
     *
     * @param \Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseDatos $pagosConBaseDato
     */
    public function removePagosConBaseDato(\Jubilaciones\DeclaracionesBundle\Entity\PagoConBaseDatos $pagosConBaseDato)
    {
        $this->pagosConBaseDatos->removeElement($pagosConBaseDato);
    }

    /**
     * Get pagosConBaseDatos
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPagosConBaseDatos()
    {
        return $this->pagosConBaseDatos;
    }
}
