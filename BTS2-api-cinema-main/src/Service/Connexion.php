<?php

namespace App\Service;


use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Security\User\JWTUser;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class Connexion {
    private UserRepository $userRepository;
    private JWTTokenManagerInterface $jwtManager;

    public function __construct(UserRepository $userRepository, JWTTokenManagerInterface $jwtManager)
    {
        $this->userRepository = $userRepository;
        $this->jwtManager = $jwtManager;
    }

    public function execute(string $email, string $mdp) : array {
        $user = $this->userRepository->findOneBy(['email' => $email]);
        // Regarde si l'utilisateur existe
        if ($user <> null) {

            if (!password_verify($mdp,$user->getPassword())) {
                // Le mot de passe ne correspond pas
                return ["erreur" => "Le mot de passe est incorrect !"];
            }

        } else {
            // L'utilisateur n'existe pas
            return ["erreur" => "L'utilisateur n'existe pas !"];
        };

        // Si il y a pas d'erreur
        $token = $this->jwtManager->create($user);
        return ["token" => $token];
    }


}
