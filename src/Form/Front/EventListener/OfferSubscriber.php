<?php

namespace App\Form\Front\EventListener;

use App\Entity\Game;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OfferSubscriber implements EventSubscriberInterface {

	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	public function __construct(TokenStorageInterface $tokenStorage) {
		$this->tokenStorage = $tokenStorage;
	}

	public static function getSubscribedEvents() {
		return [
			FormEvents::PRE_SET_DATA => 'preSetData',
		];
	}

	public function preSetData(FormEvent $event) {
	    /** @var $user User  **/
		$user = $this->tokenStorage->getToken()->getUser();
		$pmc = $user->getCoins();
		$games = $user->getGames();
		$form = $event->getForm();
		$advertKindName = $event->getData()->getAdvert()->getAdvertKind()->getName();
		switch ($advertKindName) {
			case 'Echange':
				$form->add('game', EntityType::class, [
					'label' => 'Jeu proposé',
					'class' => Game::class,
					'choice_label' => 'name',
					'choices' => $games,
					'mapped' => false
				]);
				break;
			case 'Location':
				$form->add('price', IntegerType::class, [
					'label' => 'Prix',
                    'attr' => ["min" => 0, "max" => $pmc]
				]);
				break;
		}
	}
}