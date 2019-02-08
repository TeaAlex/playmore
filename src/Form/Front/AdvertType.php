<?php

namespace App\Form\Front;

use App\Entity\Advert;
use App\Entity\AdvertKind;
use App\Entity\Game;
use App\Form\Front\EventListener\AddPlatformSubscriber;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class AdvertType extends AbstractType
{

	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorage;

	public function __construct(TokenStorageInterface $tokenStorage) {

		$this->tokenStorage = $tokenStorage;
	}

	public function buildForm(FormBuilderInterface $builder, array $options)
    {
    	/** @var $advert Advert  **/
    	$advert = $builder->getData();
    	$user = $this->tokenStorage->getToken()->getUser();
    	if($advert->getGameOwned() !== null){
    		$go = $advert->getGameOwned()->getGame();
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
            ->add('gameOwned', EntityType::class, [
            	'class' => Game::class,
	            'choice_label' => 'name',
	            'label' => 'Jeu possédé',
	            'placeholder' => '',
	            'choices' => $user->getGames(),
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
	            'label' => 'Jeu voulu',
	            'placeholder' => '',
	            'required' => false,
	            'mapped' => false,
	            'data' => $gw ?? null
            ])
        ;
        $builder->get('gameWanted')->addEventSubscriber(new AddPlatformSubscriber());
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Advert::class,
        ]);
    }
}
