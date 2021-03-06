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
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;

class RepresentanteType extends AbstractType {

  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('cuil', TextType::class)
    ->add('apellido', TextType::class)
    ->add('nombres', TextType::class)
    ->add('email', TextType::class)
    ->add('organismo', EntityType::class, array(
      'label' => "Organismo",
      'mapped' => false,
      'class' => 'Jubilaciones\\DeclaracionesBundle\\Entity\\Organismo',
      'query_builder' => function ($repositorio) {
        return $repositorio->createQueryBuilder('o')->orderBy('o.nombre', 'ASC');
        },))
        ->add('guardar', SubmitType::class, [
          'attr' => ['class' => 'save'],
        ]);


        /*
        ->add('jubi1ind', FileType::class,array(
        "label" => "Jubi1.IND",
        "attr" =>array("class" => "form-control")
      )); */


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
]); */
}

public function setDefaultOptions(OptionsResolverInterface $resolver) {
  // CONFIGURAR EL DATA_CLASS CORRECTO
  $resolver->setDefaults(array('data_class' => 'Jubilaciones\DeclaracionesBundle\Entity\Representante'));
}

public function getName() {
  return 'representante';
}

}
