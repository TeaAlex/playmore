<?php

namespace App\Controller\Front;


use App\Entity\Message;
use App\Entity\User;
use App\Form\Front\MessageType;
use App\Repository\MessageRepository;
use App\Repository\UserRepository;
use App\Security\MessageVoter;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MessageController
 * @package App\Controller\Front
 * @Route(path="/message", name="message_")
 */
class MessageController extends AbstractController 
{

	/**
	 * @Route(path="/{id}", name="show")
	 * @param User $user
	 * @param MessageRepository $messageRepository
	 *
	 * @return \Symfony\Component\HttpFoundation\Response
	 */
	public function show(User $user, MessageRepository $messageRepository): Response {
		$this->denyAccessUnlessGranted(MessageVoter::OWNER, $user->getId());
		$users = $messageRepository->findContacts($user->getId());
		return $this->render('Front/message/show.html.twig', [
			'users' => $users
		]);
	}

	/**
	 * @Route(path="/{user_from}/{user_to}", name="conversation")
	 * @param Request $request
	 * @param $user_from
	 * @param $user_to
	 * @param MessageRepository $messageRepository
	 * @param UserRepository $userRepository
	 * @param ObjectManager $em
	 *
	 * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
	 */
	public function conversation(Request $request, $user_from, $user_to,
		MessageRepository $messageRepository, UserRepository $userRepository, ObjectManager $em)
	{
		$this->denyAccessUnlessGranted(MessageVoter::OWNER, $user_from);
		$users = $messageRepository->findContacts($user_from);
		if(empty($users)){
			$userTo = $userRepository->find($user_to);
			$users[] = [
				'id' => $userTo->getId(),
				'username' => $userTo->getUsername(),
				'slug' => $userTo->getSlug(),
				'img_name' => $userTo->getImgName()
			];
		} else {
			$messages = $messageRepository->findMessages($user_from, $user_to);
		}
		$message = new Message();
		$form = $this->createForm(MessageType::class, $message);
		$form->handleRequest($request);
		if($form->isSubmitted() && $form->isValid()){
			$message->setUserFrom($userRepository->find($user_from));
			$message->setUserTo($userRepository->find($user_to));
			$em->persist($message);
			$em->flush();
			return $this->redirectToRoute('message_conversation', ['user_from' => $user_from, 'user_to' => $user_to]);
		}
		return $this->render('Front/message/show.html.twig', [
			'users' => $users,
			'messages' => $messages ?? [],
			'form' => $form->createView(),
			'user_to' => $user_to
		]);
	}
	
}