<?php

namespace App\Form;

use App\Entity\Utilisateur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class InscriptionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Votre nom']])
            ->add('prenom', TextType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Votre prenom']])
            ->add('email', TextType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Votre mail']])
            ->add('mdp', TextType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Votre mdp']])
            ->add('adresse', TextType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Votre adresse']])
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-success'], 'label' => 'Valider']);
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}