<?php

namespace Jubilaciones\DeclaracionesBundle\Form;

use Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FiltroDeclaracionjuradaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        // =================================================================================
        // Tipos de liquidacion
        $tipos = array(
            'No aplicar' => 0,
            'Normal' => Declaracionjurada::TIPO_LIQ_ORIG_NORMAL,
            'Correctiva' => Declaracionjurada::TIPO_LIQ_CORRECTIVA_NORMAL,
            'Complementaria 1' => Declaracionjurada::TIPO_LIQ_COMP_1,
            'Complementaria 2' => Declaracionjurada::TIPO_LIQ_COMP_2,
            'Complementaria 3' => Declaracionjurada::TIPO_LIQ_COMP_3,
            'Complementaria 4' => Declaracionjurada::TIPO_LIQ_COMP_4
        );
        $builder->add('tipoLiquidacion', ChoiceType::class, array('choices' => $tipos, 'data' => 0));

        // =================================================================================
        //Periodo año
        $rango = range(2011, date('Y')); // de 2015 al año actual
//        $periodo_anio = array('No aplicar');

        $periodo_anio = array_merge(array(0 => 'No aplicar'), $rango);

        //dump($periodo_anio);exit;

        $builder->add('periodoAnio', ChoiceType::class,
                array('choices' => array_flip($periodo_anio), 'data' => 0));

        // =================================================================================
        //Periodo mes
        $periodo_mes = array(
            'No aplicar' => 0,
            'Ene' => '01', 'Feb' => '02',
            'Mar' => '03', 'Abr' => '04',
            'May' => '05', 'Jun' => '06',
            '1er SAC' => '13', 'Jul' => '07',
            'Ago' => '08', 'Sep' => '09',
            'Oct' => '10', 'Nov' => '11',
            'Dic' => '12', '2do SAC' => '14');
        $builder
                ->add('periodoMes', ChoiceType::class, array(
                    'choices' => $periodo_mes,
                    'data' => 0
        ));

        // =================================================================================
        //Estados
        $estados = array_merge(array(0 => 'No aplicar'), Declaracionjurada::getEstados());
        $builder
                ->add('estado', ChoiceType::class, array(
                    'choices' => array_flip($estados),
                    'data' => 0,
                )
        );

        $builder->add('submit', SubmitType::class, array(
            'label' => 'Filtrar',
            'attr' => array('class' => 'btn btn-outline-secondary')
        ));

    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        $resolver->setDefaults(array('data_class' => null));
    }

}
