<?php

namespace AppBundle\Form;

use AppBundle\Entity\Categorie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use AppBundle\Entity\Ad;

class DeposerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        return $builder
            ->add('title', TextType::class, ['label' => 'Titre'])
            ->add('description', TextareaType::class, ['label' => 'Description'])
            ->add('city', TextType::class, ['label' => 'Ville'])
            ->add('zip', TextType::class, ['label' => 'Code postal'])
            ->add('price', IntegerType::class, ['label' => 'Prix'])
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'FirstLetterUpper',
                'placeholder' => ' -- Choisir une catégorie -- ',
                'label' => 'Catégorie',
            ])
            ->add('datecreated', DateType::class, [
                'data' => new \DateTime("now"),
                'label' => 'Date',
            ])
            ->add('terms', CheckboxType::class, [
                'label' => 'J\'accepte les conditions générales d\'utilisation',
                'mapped' => false,
                'required' => false,
            ])
            ->getForm();

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class
        ]);
    }
}
