<?php

namespace App\Form\Front\EventListener;

use App\Entity\Game;
use App\Entity\Platform;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class AddPlatformSubscriber implements EventSubscriberInterface {


	public static function getSubscribedEvents() {
		return [
			FormEvents::POST_SUBMIT => 'postSubmit',
			FormEvents::PRE_SET_DATA => 'preSetData',
		];
	}

	public function postSubmit( FormEvent $event ) {
		$form = $event->getForm();
		if($form->getData() == null){
			return;
		}
		$game = $form->getData();
		$platforms = $this->getPlatforms($game);
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

	public function preSetData(FormEvent $event) {
		if($event->getData() == null){
			return;
		}
		$form = $event->getForm();
		$platform = $event->getForm()->getParent()->getData()->getPlatform();
		$platforms = $this->getPlatforms($event->getData());
		$form->getParent()->add('platform', EntityType::class, [
			'class' => Platform::class,
			'label' => 'Plateforme',
			'choice_label' => 'name',
			'choices' => $platforms,
			'data' => $platform,
			'mapped' => false,
			'required' => false,
			'error_bubbling' => true
		]);

	}

	/**
	 * @param Game $game
	 *
	 * @return ArrayCollection
	 */
	private function getPlatforms(Game $game): ArrayCollection {
		$gamePlatforms = $game->getGamePlatforms();
		$platforms = new ArrayCollection();
		foreach ($gamePlatforms as $gamePlatform) {
			$platforms->add($gamePlatform->getPlatform());
		}

		return $platforms;
}
}