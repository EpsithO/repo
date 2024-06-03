<?php

namespace App\Controller;


use App\Repository\FilmRepository;
use App\Repository\SalleRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use App\Service\CreerReservation;
use App\Service\ReservationRequete;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api')]
class SeanceController extends AbstractController
{
    #[Route('/reservation', name: 'app_reservation_seance')]
    public function resevation(Request $request,CreerReservation $creerReservation,SerializerInterface $serializer,TokenInterface $tokenInterface): Response
    {
        // récupere les données de la requete sous form de tableau
        $donnees = json_decode($request->getContent(), true);

        $email = $tokenInterface->getUser()->getUserIdentifier();
        if ($email == "") {
            return new JsonResponse("L'utilisateur n'a pas été trouver !", 400);
        }

        // Création des classes
        $requete = new ReservationRequete($donnees["idSeance"],$donnees["nbPlace"]);


        try {
            // Créer le user
            $reservation = $creerReservation->execute($requete,$email);

            // Si pas d'erreur on renvoie le User avec un status 201
            $reservationSerialized = $serializer->serialize($reservation, 'json', ['groups' => 'info_reservation']);
            return new Response($reservationSerialized, 201, [
                'content-type' => 'application/json'
            ]);
        } catch (\Exception $e) {
            // Si erreur on renvoie status 400 avec l'erreur
            return new JsonResponse($e->getMessage(), 400);
        }
    }
    #[Route('/info-seance', name: 'app_info_seance')]
    public function infoSeance(Request $request,CreerReservation $creerReservation,SerializerInterface $serializer,TokenInterface $tokenInterface, SeanceRepository $seanceRepository,FilmRepository $filmRepository, SalleRepository $salleRepository): Response
    {
        $donnees = json_decode($request->getContent(), true);
        $idSeance = $donnees["idSeance"];
        if ($idSeance == null) {
            // Si erreur on renvoie status 400 avec l'erreur
            return new JsonResponse("L'ID de la seance n'a pas ete trouve", 400);
        }

        $seance = $seanceRepository->findOneBy(["id" => $idSeance]);
        if ($seance == null) {
            // Si erreur on renvoie status 400 avec l'erreur
            return new JsonResponse("La seance n'a pas ete trouve", 400);
        }

        // Si pas d'erreur on renvoie le User avec un status 201
        $seanceSerialized = $serializer->serialize($seance, 'json', ['groups' => 'info_seance']);
        return new Response($seanceSerialized, 201, [
            'content-type' => 'application/json'
        ]);

    }
}
