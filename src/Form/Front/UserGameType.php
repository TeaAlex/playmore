<?php

namespace App\Form\Front;


use App\Entity\Item;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGameType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		$builder->add('items', EntityType::class, [
			'class' => Item::class,
			'choice_label' => 'name',
			'multiple' => true,
			'expanded' => true
		]);
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults([
			'data_class' => User::class
		]);
	}

}