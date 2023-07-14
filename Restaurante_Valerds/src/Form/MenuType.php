<?php

namespace App\Form;

use App\Entity\Menu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
class MenuType extends AbstractType
{


    private $choices;
public function _construct(array $choices){

}

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('idCategoria',ChoiceType::class, array(
             'choices' => $options['categorias'],
             'placeholder'=>'Seleccionar'))
            ->add('nombre')
            ->add('descripcion')
            ->add('precio')
            ->add('estado')
        ;
    }
    public function configureOptions(OptionsResolver $resolver)
    {
         $resolver->setDefaults(array(
        'categorias' => array(),
    ));
    }


}
