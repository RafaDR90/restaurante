<?php
// src/Form/UserType.php

namespace App\Form;

use App\Entity\Restaurante;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditarPerfilType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $restaurante = $options['data'];

        $builder
            ->add('email', EmailType::class, [
                'label' => 'Correo electrónico'
            ])
            // Quiero hacer que si el campo de la contraseña esta vacio no se intente cambiar
            ->add('password', PasswordType::class, [
                'label' => 'Contraseña',
                'required' => false,
                'mapped' => false,
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección'
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Guardar cambios'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Restaurante::class,
        ]);
    }
}
