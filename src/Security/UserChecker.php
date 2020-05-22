<?php

namespace App\Security;

use App\Exception\AccountDeletedException;
use App\Security\User as AppUser;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
 
    }

    public function checkPostAuth(UserInterface $user)
    {
         // user account is inactive, the user may be notified
        if (!($user->getStatus())) {
            throw new AccountExpiredException('Compte inactif, contactez l\'administrateur');
        }
    }
}
