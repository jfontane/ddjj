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


class DeclaracionjuradaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('tipoLiquidacion', ChoiceType::class, array(
                    'choices' => array('normal' => 'Normal', 'correctiva' => 'Correctiva',
                                       'C1' => 'Complementaria 1', 'C2' => 'Complementaria 2',
                                       'C3' => 'Complementaria 3', 'C4' => 'Complementaria 4')
                ));
                
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        // CONFIGURAR EL DATA_CLASS CORRECTO
        $resolver->setDefaults(array('data_class' => 'CursoSymfony\EventosBundle\Entity\Disertante'));
    }

    public function getName() {
        return 'disertante';
    }

}
