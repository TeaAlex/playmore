<?php

namespace App\Form;

use App\Entity\Category;
use App\Entity\Classification;
use App\Entity\Developper;
use App\Entity\Editor;
use App\Entity\Game;
use App\Entity\Platform;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GameType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
	            "label" => "Nom",
	            ])
            ->add('imgFile', FileType::class, [
	            "label" => "Cover",
	            "required" => false
            ])
            ->add('releaseDate', DateType::class, [
            	"label" => "Date de sortie",
            	"widget" => "single_text"
            ])
            ->add('multiplayer', CheckboxType::class, [
	            "label" => "Multijoueur",
	            "required" => false
            ])
            ->add('description', TextareaType::class, [
	            "label" => "Description",
            ])
            ->add('platform', EntityType::class, [
            	"label" => "Plateforme(s)",
            	"class" => Platform::class,
	            "choice_label" => "name",
	            "multiple" => true,
	            "expanded" => true,
            ])
	        ->add('developper', EntityType::class, [
		        "label" => "Développeur",
		        "class" => Developper::class,
		        "choice_label" => "name",
	        ])
            ->add('editor', EntityType::class, [
            	"label" => "Editeur",
	            "class" => Editor::class,
	            "choice_label" => "name",
            ])
            ->add('classification', EntityType::class, [
            	"label" => "Classification PEGI",
	            "class" => Classification::class,
	            "choice_label" => "name",
            ])
            ->add('category', EntityType::class, [
            	"label" => "Catégorie",
	            "class" => Category::class,
	            "choice_label" => "name",
	            "required" => false
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Game::class,
        ]);
    }
}
