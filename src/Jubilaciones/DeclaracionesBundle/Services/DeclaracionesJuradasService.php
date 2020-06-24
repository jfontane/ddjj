<?php

namespace Jubilaciones\DeclaracionesBundle\Services;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Jubilaciones\DeclaracionesBundle\Entity\Organismo;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use function ctype_digit;
use function dump;

/**
 * Description of DeclaracionesJuradasService
 *
 * @author esangoi
 */
class DeclaracionesJuradasService {

    /**
     *
     * @var EntityManager 
     */
    private $em;

    /**
     *
     * @var Filesystem 
     */
    private $fs;

    /**
     *
     * @var KernelInterface 
     */
    private $kernel;

    /**
     *
     * @var string 
     */
    private $msg;

    /**
     *
     * @var string
     */
    const ALIAS_LIQ = 'l';

    /**
     *
     * @var string
     */
    const ALIAS_ORG = 'o';

    public function __construct(EntityManager $em, Filesystem $filesystem, KernelInterface $kernel) {
        $this->em = $em;
        $this->fs = $filesystem;
        $this->kernel = $kernel;
    }

    /**
     * array:4 [▼
     *   "tipoLiquidacion" => "1"
     *   "periodoAnio" => "2020"
     *   "periodoMes" => "01"
     *   "estado" => 0
     * ]
     * 
     * @param array $filtros
     */
    public function filtrar($filtros, $zona = null, $organismo = null) {

        //Instanciar
        $qbLiq = $this->em->createQueryBuilder();
        $qbLiq->select(array(self::ALIAS_LIQ));
        $qbLiq->from(Declaracionjurada::class, self::ALIAS_LIQ);
        $qbLiq->innerJoin(Organismo::class, self::ALIAS_ORG, Join::WITH,
                self::ALIAS_LIQ . '.organismo = ' . self::ALIAS_ORG . '.id');
        $qbLiq->orderBy(self::ALIAS_LIQ . '.fechaEntrega', 'DESC');
//        $qbLiq->addOrderBy(self::ALIAS_LIQ . '.periodoAnio', 'DESC');
//        $qbLiq->addOrderBy(self::ALIAS_LIQ . '.periodoMes', 'DESC');

        $andX = $qbLiq->expr()->andX();
        foreach ($filtros as $filtro => $value) {
            
            if($value === 0){
                continue;
            }

            $prop = self::ALIAS_LIQ . '.' . $filtro;
            $param = ':' . $filtro;
            $andX->add($qbLiq->expr()->eq($prop, $param));

            //Para el estado necesita el nombre del estado
            if ($filtro == 'estado' && ctype_digit($value)) {
                $qbLiq->setParameter($param, Declaracionjurada::getNomEstado($value));
            } else {
                $qbLiq->setParameter($param, $value);
            }
        }
        
        if($zona){
//            dump($zona);exit;
            $andX->add($qbLiq->expr()->eq(self::ALIAS_ORG . '.zona', ':zona'));
            $qbLiq->setParameter(':zona', $zona);
        }
//        dump($organismo);exit;
         if($organismo instanceof Organismo){
//            dump($zona);exit;
            $andX->add($qbLiq->expr()->eq(self::ALIAS_ORG . '.codigo', ':codigo'));
            $qbLiq->setParameter(':codigo', $organismo->getCodigo());
        }
        
        /**
         * Si los filtros vienen vacios no se debe agregar ninguna
         * clausula where.         
         */
        if ($andX->count() > 0) {
            $qbLiq->andWhere($andX);
        }
        
        return $qbLiq->getQuery();
    }

//    public function setTotalizadores() {
//        $ddjjsBasePath = $this->kernel->getRootDir() . '/../web/uploads/';
////        file($this->kernel->getRootDir() . '/../web/uploads/' . $fileName);
//        //$directory = '/path/to/my/directory';
//        $archivos = array_diff(scandir($ddjjsBasePath), array('..', '.'));
//
//        $resultado = array();
//        foreach ($archivos as $nom_archivo) {
//
//            if (!stripos($nom_archivo, '.dat')) {
//                continue;
//            }
//
//            $path = $ddjjsBasePath . $nom_archivo;
//            $file = file($path);
//            $totales = \Jubilaciones\DeclaracionesBundle\Classes\Util::totaliza($file);
//
//            $resultado[$nom_archivo] = $totales;
//            
////            break;
//        }
//
//        return $resultado;
//    }
}
