<?php

namespace App\Form;

use App\Entity\Home;
use App\Entity\Carousel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class CarouselType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('imageName')
            ->add('titre', TextType::class,['label'=>'titre','required'=> true,])
            ->add('texte', TextareaType::class,['label'=>'texte','required'=> false])
            ->add('imageFile', FileType::class,['label'=>'Image', 'required'=> true])
            ->remove('updatedAt', null, ['widget' => 'single_text'])
            ->add('homes', EntityType::class, ["class"=>Home::class, "label"=>"homes","multiple"=>true, "attr"=>["class"=>"select2"]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Carousel::class,
        ]);
    }
}
