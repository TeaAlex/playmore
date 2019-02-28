<?php

namespace App\Security;


use App\Entity\Offer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class MessageVoter extends Voter {

	const OWNER = 'owner';

	protected function supports($attribute, $subject) {
		if(!in_array($attribute, [self::OWNER])){
			return false;
		}
		return true;
	}


	protected function voteOnAttribute($attribute, $subject, TokenInterface $token) {
		$user = $token->getUser();
		if (!$user instanceof User) {
			return false;
		}
		switch ($attribute) {
			case self::OWNER:
				return $user->getId() == $subject;
		}

	}
}