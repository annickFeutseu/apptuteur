<?php

namespace App\Form;

use App\Entity\Etudiant;
use App\Entity\Tuteur;
use Doctrine\DBAL\Types\TextType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EtudiantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('prenom')
            ->add('formation')
            // ->add('tuteur', EntityType::class, [
            //     'class' => Tuteur::class,
            //     'choice_label' => 'id',
            // ])
        ;

        // $builder
        //     ->add('nom', TextType::class, [
        //         'attr' => ['class' => 'form-control mb-3']
        //     ])
        //     ->add('prenom', TextType::class, [
        //         'attr' => ['class' => 'form-control mb-3']
        //     ])
        //     ->add('formation', TextType::class, [
        //         'attr' => ['class' => 'form-control mb-3']
        //     ]);

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Etudiant::class,
        ]);
    }
}
