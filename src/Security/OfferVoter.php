<?php

namespace App\Security;


use App\Entity\Offer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class OfferVoter {

	const OWNER = 'owner';

	protected function supports($attribute, $subject) {
		if(!in_array($attribute, [self::OWNER])){
			return false;
		}
		if(!$subject instanceof Offer){
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
				return $this->isOwner($user, $subject);
		}

	}

	public function isOwner(User $user, Offer $offer): bool {
		return $user === $offer->getCreatedBy();
	}
}