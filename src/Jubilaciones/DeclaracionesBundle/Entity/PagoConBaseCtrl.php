<?php

namespace Jubilaciones\DeclaracionesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * PagoConBase
 *
 * @ORM\Table(name="PagoConBaseCtrl")
 * @ORM\Entity()
 */
class PagoConBaseCtrl {

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity="PagoConBaseCtrlHr", mappedBy="pagoConBaseCtrlHr")
     */
    private $pagosConBaseCtrlHr;

    /**
     * @ORM\OneToMany(targetEntity="PagoConBaseCtrlTr", mappedBy="pagoConBaseCtrlTr")
     */
    private $pagosConBaseCtrlTr;

    /**
     * @ORM\OneToMany(targetEntity="PagoConBaseCtrlDatos", mappedBy="pagoConBaseCtrlDatos")
     */
    private $pagosConBaseCtrlDatos;


}
