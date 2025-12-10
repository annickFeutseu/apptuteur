<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Tuteur;
use App\Entity\Visite;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VisiteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateTimeType::class, [
                'widget' => 'single_text',
                'attr' => ['class' => 'form-control mb-3']
            ])
            ->add('commentaire', TextareaType::class, [
                'attr' => ['class' => 'form-control mb-3']
            ])
            // ->add('tuteur', EntityType::class, [
            //     'class' => Tuteur::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('etudiant', EntityType::class, [
            //     'class' => Etudiant::class,
            //     'choice_label' => 'id',
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Visite::class,
        ]);
    }
}
