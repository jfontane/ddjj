<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Organismo
 *
 * @ORM\Table(name="conveniocuota")
 * @ORM\Entity(repositoryClass="Jubilaciones\DeclaracionesBundle\Repository\ConveniocuotaRepository")
 */
class Conveniocuota {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="10",max="13")
     */
    protected $codigoConvenio;
    
    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     * @Assert\Length(min="10",max="10")
     */
    protected $codigoOrganismo;

    /**
     * @ORM\Column(type="string")
     */
    protected $tramo;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $cuota;

    /**
     * @ORM\Column(type="boolean")
     */
    protected $pagado;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $vencimiento1;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $importe1;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $vencimiento2;
    
    /**
     * @ORM\Column(type="string")
     */
    protected $importe2;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $vencimiento3;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $importe3;
    
    /**
     * @ORM\ManyToOne(targetEntity="Organismo", inversedBy="convenioscuotas")
     * @ORM\JoinColumn(name="organismo_id", referencedColumnName="id")
     */
    protected $organismo;
    
    
}
