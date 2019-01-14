<?php

namespace App\Form;

use App\Entity\Advert;
use App\Entity\AdvertKind;
use App\Entity\Item;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('advertKind', EntityType::class, [
            	'class' => AdvertKind::class,
	            'choice_label' => 'name',
	            'label' => 'Type d\'annonce',
	            'placeholder' => ''
            ])
            ->add('startDate', DateType::class, [
            	'widget' => 'single_text',
	            'label' => 'Date de début',
	            'data' => new \DateTime('now'),
	            'attr' => ["class" => ""]
            ])
            ->add('endDate', DateType::class, [
	            'widget' => 'single_text',
	            'label' => 'Date de fin',
	            'data' => new \DateTime('now')
            ])
            ->add('itemOwned', EntityType::class, [
            	'class' => Item::class,
	            'choice_label' => 'name',
	            'label' => 'Objet possédé',
	            'placeholder' => ''
            ])
            ->add('price', IntegerType::class, [
            	'label' => 'Prix',
	            'required' => false
            ])
            ->add('itemWanted', EntityType::class, [
            	'class' => Item::class,
	            'choice_label' => 'name',
	            'label' => 'Objet voulu',
	            'placeholder' => '',
	            'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
