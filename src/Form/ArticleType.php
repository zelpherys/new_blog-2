<?php
// src/Form/ArticleType.php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Categorie;
use App\Entity\SousCategorie;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use App\Repository\CategorieRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use App\Repository\SousCategorieRepository;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;


class ArticleType extends AbstractType
{
    private $sousCategorieRepository;

    public function __construct(SousCategorieRepository $sousCategorieRepository)
    {
        $this->sousCategorieRepository = $sousCategorieRepository;
    }
public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('titre_article', TextType::class, ['attr' => ['class' => 'form-control', 'placeholder' => 'Votre Titre']])
        ->add('contenu_article', CKEditorType::class, ['attr' => ['class' => 'form-control']])

        ->add('nomImage', FileType::class, ['attr'=>['class'=>'form'], 'mapped'=>false, 'multiple'=>true])
        ->add('idCategorie', EntityType::class, [
            'class' => Categorie::class,
            'choice_label' => 'nomCategorie',
            'label' => false,
            'attr' => ['class' => 'form-select'],
            'placeholder' => 'Sélectionnez une catégorie', 
            'mapped' => false
        ]);
        $formModifier = function(FormInterface $form, Categorie $categorie = null){ 
            $sousCategories = null === $categorie ? [] : $this->sousCategorieRepository->findByIdCategorie($categorie);
$form->add('idSousCategorie', EntityType::class, [
                'class' => SousCategorie::class,
                'placeholder' => 'Choisissez la sous-catégorie',
                'choice_label' => 'nomSousCategorie', 
                'choices' => $sousCategories,
                'multiple' => false,
                'label' => false,
                'attr'=> ['class'=> 'form-select text-center mt-2'
                ]]);
            };

            //->add('dateAnnonce')
            //->add('latitude')
            //->add('longitude')
            $builder->get('idCategorie')->addEventListener( 
                FormEvents::POST_SUBMIT,
                function(FormEvent $event) use ($formModifier){
                    $categorie = $event->getForm()->getData();
                    $formModifier($event->getForm()->getParent(), $categorie);
                }
            );
            $builder->addEventListener( 
                FormEvents::PRE_SET_DATA,
                function (FormEvent $event) use ($formModifier) {
                    $data = $event->getData();
                    $formModifier($event->getForm(), $data->getIdSousCategorie());
                }
            )
            ->add('save', SubmitType::class, ['attr' => ['class' => 'btn btn-success'], 'label' => 'Valider']);
}

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'allow_extra_fields'=>true
        ]);
    }
}