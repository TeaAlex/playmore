<?php

namespace App\Security;


use App\Entity\Offer;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class OfferVoter extends Voter {

	const PENDING = 'pending';

	protected function supports($attribute, $subject) {
		if(!in_array($attribute, [self::PENDING])){
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
			case self::PENDING:
				return $this->isPending($user, $subject);
		}

	}

	public function isPending(User $user, Offer $offer): bool {
		$offerStatus = $offer->getOfferStatus();
		return $user === $offer->getCreatedBy() && $offerStatus->getName() === 'En cours de validation' ;
	}
}