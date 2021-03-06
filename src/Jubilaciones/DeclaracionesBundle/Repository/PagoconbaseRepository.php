<?php

namespace Jubilaciones\DeclaracionesBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query;

/**
 * DeclaracionjuradaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PagoconbaseRepository extends EntityRepository {

    public function findDeclaracionesPorPeriodo($anio,$mes) {
        $em = $this->getEntityManager();
        $dql = "SELECT d
                FROM JubilacionesDeclaracionesBundle:Declaracionjurada d
                WHERE d.periodoanio = :anio and d.periodoMes = :mes";
        return $em->createQuery($dql)->setParameters(array('anio'=> $anio, 'mes'=>$mes))->getResult();
    }


}
