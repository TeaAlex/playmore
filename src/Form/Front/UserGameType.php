<?php

namespace App\Form\Front;


use App\Entity\Game;
use App\Entity\GamePlatform;
use App\Form\Front\EventListener\AddPlatformSubscriber;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserGameType extends AbstractType {

    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(GameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    public function buildForm( FormBuilderInterface $builder, array $options ) {
		/** @var $gamePlatform GamePlatform  **/
		$userId = $options["userId"];
		$gamePlatform = $builder->getData();
		$choices = $this->gameRepository->findGameNotOwned($userId);
		$builder->add('game', EntityType::class, [
			'class' => Game::class,
			'choice_label' => 'name',
			'mapped' => false,
			'required' => false,
			'data' => $gamePlatform->getGame(),
            'choices' => $choices
		]);

		$builder->get('game')->addEventSubscriber(new AddPlatformSubscriber());
	}

	public function configureOptions( OptionsResolver $resolver ) {
		$resolver->setDefaults([
			'data_class' => GamePlatform::class,
            'userId' => null
		]);
	}

}