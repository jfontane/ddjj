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
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;

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
                        '2016' => '2016', '2015' => '2015',
                        '2014' => '2014', '2013' => '2013',
                        '2012' => '2012', '2011' => '2011')
                ))
                ->add('periodoMes', ChoiceType::class, array(
                    'choices' => array('Ene' => '01', 'Feb' => '02',
                        'Mar' => '03', 'Abr' => '04',
                        'May' => '05', 'Jun' => '06',
                        '1er SAC' => '13', 'Jul' => '07',
                        'Ago' => '08', 'Sep' => '09',
                        'Oct' => '10', 'Nov' => '11',
                        'Dic' => '12', '2do SAC' => '14')
                ))
                 ->add('jubidat', FileType::class,array(
                      'label' => "Jubi.dat",
                      'mapped' => false,
                      'constraints' => [
                    new File([
                        'maxSize' => '1548k',
                        ])
                ],))
                ->add('jubi1ind', FileType::class,array(
                      'label' => "Jubi1.ind",
                      'mapped' => false,
                      'constraints' => [
                    new File([
                        'maxSize' => '1548k',
                        ])
                ],));


        /*
                 ->add('jubi1ind', FileType::class,array(
                "label" => "Jubi1.IND",
                "attr" =>array("class" => "form-control")
            ));*/


                /*
                ->add('jubidat', FileType::class, [
                    'label' => 'Jubi.dat',
                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,
                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => false,
                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'multipart/form-data',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid TXT document',
                                ])
                    ],
                ])
                ->add('jubi1ind', FileType::class, [
                    'label' => 'Jubi1.ind',
                    // unmapped means that this field is not associated to any entity property
                    'mapped' => false,
                    // make it optional so you don't have to re-upload the PDF file
                    // every time you edit the Product details
                    'required' => false,
                    // unmapped fields can't define their validation using annotations
                    // in the associated entity, so you can use the PHP constraint classes
                    'constraints' => [
                        new File([
                            'maxSize' => '1024k',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid PDF document',
                                ])
                    ],
        ]);*/
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver) {
        // CONFIGURAR EL DATA_CLASS CORRECTO
        $resolver->setDefaults(array('data_class' => 'Jubilaciones\DeclaracionesBundle\Entity\Declaracionjurada'));
    }

    public function getName() {
        return 'declaracionjurada';
    }

}
