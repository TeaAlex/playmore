<?php

namespace App\Form\Front;

use App\Entity\Advert;
use App\Entity\AdvertKind;
use App\Entity\Game;
use App\Form\Front\EventListener\AddPlatformSubscriber;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdvertType extends AbstractType
{

	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;
    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(TokenStorageInterface $tokenStorage, GameRepository $gameRepository) {

		$this->tokenStorage = $tokenStorage;
        $this->gameRepository = $gameRepository;
    }

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	/** @var $advert Advert  **/
    	$advert = $builder->getData();
    	$user = $this->tokenStorage->getToken()->getUser();
    	$gameOwned = $this->gameRepository->findGameNotInAdvertByUser($user->getId());
    	if($advert->getGameOwned() !== null){
    		$go = $advert->getGameOwned()->getGame();
    		$gameOwned[] = $go;
	    }
    	if( $advert->getGameWanted() !== null ){
    	    $gw = $advert->getGameWanted()->getGame();
	    }
        $builder
            ->add('advertKind', EntityType::class, [
            	'class' => AdvertKind::class,
	            'choice_label' => 'name',
	            'label' => 'Type d\'annonce',
	            'placeholder' => ''
            ])
            ->add('gameOwned', EntityType::class, [
            	'class' => Game::class,
	            'choice_label' => 'name',
	            'label' => 'Jeu possédé',
	            'placeholder' => '',
	            'choices' => $gameOwned,
	            'mapped' => false,
	            'data' => $go ?? null
            ])
            ->add('price', IntegerType::class, [
            	'label' => 'Prix',
	            'required' => false
            ])
            ->add('gameWanted', EntityType::class, [
            	'class' => Game::class,
	            'choice_label' => 'name',
	            'label' => 'Jeu recherché',
	            'placeholder' => '',
	            'required' => false,
	            'mapped' => false,
	            'data' => $gw ?? null
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => false
            ])
        ;
        $builder->get('gameWanted')->addEventSubscriber(new AddPlatformSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
            'gameOwned' => null
        ]);
    }
}
