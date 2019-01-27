<?php

namespace App\Form\Front;


use App\Entity\Game;
use App\Entity\Platform;
use App\Entity\User;
use App\Form\Front\EventListener\AddPlatformSubscriber;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGameType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {

		$builder->add('game', EntityType::class, [
			'class' => Game::class,
			'choice_label' => 'name',
			'mapped' => false,
			'required' => false
		]);

		$builder->get('game')->addEventSubscriber(new AddPlatformSubscriber());
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults([
			'data_class' => User::class
		]);
	}

}