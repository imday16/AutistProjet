<?php

namespace App\Form;

use App\Entity\Home;

use App\Entity\Carousel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class HomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre', TextType::class,[
                'label'=>'titre de la page',
                'required'=> true,
            ])
            ->add('texte', TextareaType::class, [
                'label'=>'Texte',
                'required'=> false,
            ])
            ->remove('carousels', EntityType::class, [
                'class'=>Carousel::class, "label"=>"Carousel", "multiple"=> true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Home::class,
        ]);
    }
}
