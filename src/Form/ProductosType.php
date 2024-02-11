<?php

namespace App\Form;

use App\Entity\Categorias;
use App\Entity\Productos;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;


class ProductosType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $catId = $options['catId'];
        $builder
            ->add('nombre')
            ->add('descripcion')
            ->add('peso')
            ->add('stock')
            ->add('categoria', EntityType::class, [
                'class' => Categorias::class,
                'choice_label' => 'nombre', // Cambia 'id' al campo que deseas mostrar
                'query_builder' => function (EntityRepository $er) use ($catId) {
                    return $er->createQueryBuilder('c')
                        ->andWhere('c.id = :catId')
                        ->setParameter('catId', $catId);
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Productos::class,
            'catId' => '',
        ]);
    }
}
