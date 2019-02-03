<?php
namespace App\Form\Front;
use App\Entity\Platform;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class UserType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// TODO FIX EDIT PWD IF BLANK
		$builder
			->add('username', TextType::class, [ 'label' => 'Nom d\'utilisateur', ])
			->add('email', EmailType::class)
			->add('imgFile', FileType::class, [
				"label" => "Cover",
				"required" => false
			])
//			->add('roles', ChoiceType::class, [
//				'choices'  => [
//					'User' => 'ROLE_USER',
//					'Admin' => 'ROLE_ADMIN'
//				],
//				'multiple' => true,
//				'expanded' => true
//			])
			->add('platforms', EntityType::class, [
				'class' => Platform::class,
				'choice_label' => 'name',
				'multiple' => true,
				'expanded' => true
			])
		;
	}
	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults(array(
			'data_class' => User::class
		));
	}
}