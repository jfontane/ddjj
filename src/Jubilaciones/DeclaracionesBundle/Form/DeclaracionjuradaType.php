<?php

namespace Jubilaciones\DeclaracionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;


class DeclaracionjuradaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tipoLiquidacion', ChoiceType::class, array(
                    'choices' => array('Normal' => '1', 'Correctiva' => '2',
                                       'Complementaria 1' => '3', 'Complementaria 2' => '4',
                                       'Complementaria 3' => '5', 'Complementaria 4' => '6')
                ))
                ->add('periodoAnio', ChoiceType::class, array(
                    'choices' => array('2020' => '2020', '2019' => '2019',
                                       '2018' => '2018', '2017' => '2017',
                                       '2016' => '2016', '2015' => '2015')
                ))
                ->add('periodoMes', ChoiceType::class, array(
                    'choices' => array('Ene' => '01', 'Feb' => '02',
                                       'Mar' => '03', 'Abr' => '04',
                                       'May' => '05', 'Jun' => '06',
                                       '1er SAC' => '13', 'Jul' => '07',
                                       'Ago' => '08', 'Sep' => '09',
                                       'Oct' => '10', 'Nov' => '11',
                                       'Dic' => '12', '2do SAC' => '14')
                ));
                
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        // CONFIGURAR EL DATA_CLASS CORRECTO
        $resolver->setDefaults(array('data_class' => 'Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada'));
    }

    public function getName() {
        return 'declaracionjurada';
    }

}
