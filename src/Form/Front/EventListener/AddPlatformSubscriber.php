<?php

namespace App\Form\Front\EventListener;

use App\Entity\Platform;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddPlatformSubscriber implements EventSubscriberInterface {


	public static function getSubscribedEvents() {
		return [FormEvents::POST_SUBMIT => 'postSubmit'];
	}

	public function postSubmit( FormEvent $event ) {
		$form = $event->getForm();
		if($form->getData() == null){
			return;
		}
		$gamePlatforms = $form->getData()->getGamePlatforms();
		$platforms = new ArrayCollection();
		foreach ( $gamePlatforms as $gamePlatform ) {
			$platforms->add($gamePlatform->getPlatform());
		}
		$form->getParent()->add('platform', EntityType::class, [
			'class' => Platform::class,
			'label' => 'Plateforme',
			'choice_label' => 'name',
			'choices' => $platforms,
			'data' => $platforms->first(),
			'mapped' => false,
			'required' => false,
			'error_bubbling' => true
		]);
	}
}