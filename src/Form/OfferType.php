<?php

namespace App\Form;

use App\Entity\Offer;
use App\Form\Front\EventListener\OfferSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class OfferType extends AbstractType
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
    	/** @var $offer Offer  **/
    	$offer = $builder->getData();
        $builder
            ->add('startDate', DateType::class, [
	            'widget' => 'single_text',
	            'label' => 'Date de début',
	            'data' => new \DateTime('now')
            ])
            ->add('endDate', DateType::class, [
	            'widget' => 'single_text',
	            'label' => 'Date de fin',
	            'data' => new \DateTime('tomorrow')
            ])
        ;

        $builder->addEventSubscriber(new OfferSubscriber($this->tokenStorage));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
