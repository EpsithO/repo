<?php

namespace App\Controller;


use App\Repository\UserRepository;
use App\Service\Connexion;
use App\Service\CreerUser;
use App\Service\CreerUserRequete;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api')]
class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_user_inscription')]
    public function inscription(Request $request,ValidatorInterface $validateur,UserRepository $userRepository,EntityManagerInterface $entityManager,SerializerInterface $serializer): Response|JsonResponse
    {
        // récupere les données de la requete sous form de tableau
        $donnees = json_decode($request->getContent(), true);
        // Création des classes
        $requete = new CreerUserRequete($donnees["email"],$donnees["password"]);
        $creerUser = new CreerUser($validateur,$userRepository,$entityManager);

        try {
            // Créer le user
            $user = $creerUser->execute($requete);
            // Si pas d'erreur on renvoie le User avec un status 201
            $userSerialized = $serializer->serialize($user, 'json', ['groups' => 'info_user']);
            return new Response($userSerialized, 201, [
                'content-type' => 'application/json'
            ]);
        } catch (\Exception $e) {
            // Si erreur on renvoie status 400 avec l'erreur
            return new JsonResponse($e->getMessage(), 400);
        }

    }

    #[Route('/login', name: 'app_user_login')]
    public function login(Request $request,Connexion $connexion): Response|JsonResponse
    {
        // récupere les données de la requete sous form de tableau
        $donnees = json_decode($request->getContent(), true);
        // Création des classes
        $email = $donnees["email"] ;
        $password = $donnees["password"];
        $reponse = $connexion->execute($email,$password);

        if (!isset($reponse["token"])) {
            return new JsonResponse($reponse,400);
        } else {
            return new JsonResponse(['token' => $reponse["token"]],200);
        }

    }
}
