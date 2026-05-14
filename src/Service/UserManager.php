<?php
namespace App\Service;

use App\Entity\User;

class UserManager
{
    public function validate(User $user): bool
    {
        // Règle 1: Email valide
        if (!filter_var($user->getEmail(), FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('Email invalide');
        }
        
        // Règle 2: Mot de passe >= 8 caractères (utilisez getPasswordHash)
        if (strlen($user->getPasswordHash()) < 8) {
            throw new \InvalidArgumentException('Le mot de passe doit contenir au moins 8 caractères');
        }
        
        return true;
    }
}