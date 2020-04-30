<?php

namespace Jubilaciones\DeclaracionesBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;


class ImportacionType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add('nombre', ChoiceType::class, array(
                    'choices' => array('Usuarios' => 'Usuarios', 'Organismos' => 'Organismos', 'Declaraciones' => 'Declaraciones')))
                ->add('archivo', FileType::class,array(
                     'label' => "Archivo",
                     'mapped' => false,
                     'constraints' => [
                   new File([
                       'maxSize' => '1548k',
                       ])
               ],))
               ->add('descripcion', TextType::class);

        //->add('Guardar',SubmitType::class, array('label' => 'Nuevo Usuario'));
    }

    public function configureOptions(OptionsResolver $resolver) {
        $resolver->setDefaults(array('data_class' => 'Jubilaciones\DeclaracionesBundle\Entity\Importacion'));
    }

}
