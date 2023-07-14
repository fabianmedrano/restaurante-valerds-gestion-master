<?php

namespace App\Form;

use App\Entity\Usuarios;
use App\CustomForm\PermisosUsuarioType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class UsuariosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('usuario')
            ->add('contrasena')
            ->add('estado')
            ->add('nombre')
            ->add('correo')
        ;
        $builder->add('permisosUsuario', CollectionType::class, [
          'entry_type' => PermisosUsuarioType::class,
          'entry_options' => ['label' => false],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Usuarios::class,
        ]);
    }
}
