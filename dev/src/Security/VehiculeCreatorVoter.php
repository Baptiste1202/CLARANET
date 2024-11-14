<?php 
namespace App\Security;

use App\Entity\User;
use App\Entity\Vehicule;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class VehiculeCreatorVoter extends Voter{

    protected function supports(string $attribute, mixed $subject): bool
    {
        return 'vehicule.is_creator' == $attribute && $subject instanceof Vehicule; 
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        if (!$user instanceof User){
            return false; 
        }

        return $user == $subject->getUserCreator();
    }

}