<?php

namespace App\Security;

use App\Entity\Formation;

use App\Entity\Utilisateurs;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class FormationVoter extends Voter
{
    // Define your attributes
    public const EDIT = 'EDIT';
    public const DELETE = 'DELETE';

    protected function supports(string $attribute, $subject): bool
    {
        // Only vote on Formation objects and specific attributes
        return $subject instanceof Formation && 
               in_array($attribute, [self::EDIT, self::DELETE]);
    }

    protected function voteOnAttribute(string $attribute, $formation, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // If user is not logged in, deny access
        if (!$user instanceof Utilisateurs) {
            return false;
        }

        // Check permissions based on attribute
        return match ($attribute) {
            self::EDIT => $this->canEdit($user),
            self::DELETE => $this->canDelete($user),
            default => false
        };
    }

    private function canEdit(Utilisateurs $user): bool
    {
        // Only ADMIN and GESTIONNAIRE can edit
        return in_array($user->getRole(), ['ROLE_ADMIN', 'ROLE_GESTIONNAIRE']);
    }

    private function canDelete(Utilisateurs $user): bool
    {
        // Only ADMIN can delete
        return $user->getRole() === 'ROLE_ADMIN';
    }
}