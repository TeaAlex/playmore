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

class UserGameType extends AbstractType {

	public function buildForm( FormBuilderInterface $builder, array $options ) {
		/** @var $gamePlatform GamePlatform  **/
		$userId = $options["userId"];
		$gamePlatform = $builder->getData();
		$builder->add('game', EntityType::class, [
			'class' => Game::class,
			'choice_label' => 'name',
			'mapped' => false,
			'required' => false,
			'data' => $gamePlatform->getGame(),
            'query_builder' => function(EntityRepository $repository) use ($userId) {
		        return $repository->createQueryBuilder('g')
                    ->join('g.gamePlatforms', 'gp' )
                    ->join('gp.user', 'gpu')
                    ->where('gpu != :userId')
                    ->setParameter('userId', $userId)
                ;
            }
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