<?php

namespace App\Service;


use App\Entity\Reservation;
use App\Entity\User;
use App\Repository\ReservationRepository;
use App\Repository\SeanceRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerReservation {
    private ValidatorInterface $validateur;
    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;
    private SeanceRepository $seanceRepository;
    private ReservationRepository $reservationRepository;

    public function __construct(ValidatorInterface $validateur,UserRepository $userRepository,EntityManagerInterface $entityManager, SeanceRepository $seanceRepository, ReservationRepository $reservationRepository)
    {
        $this->validateur = $validateur;
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->seanceRepository = $seanceRepository;
        $this->reservationRepository = $reservationRepository;
    }

    public function execute(ReservationRequete $requete,string $emailUser) :  ?Reservation {
        // Valider les données en entrées (de la requête)
        $erreur = $this->validateur->validate($requete);
        $messageErreur = "";

        // Ajoute toutes les erreurs dans un seul message
        if ($erreur->count()<> 0) {
            $messageErreur = ($erreur->get(0))->getMessage();
            for ($i=1;$i<$erreur->count();$i++) {
                $messageErreur .= " et ".($erreur->get($i))->getMessage();
            }
            throw new \Exception($messageErreur);
        }


        // Vérifier que l'utilisateur existe
        $user = $this->userRepository->findOneBy(["email" => $emailUser]);
        if ($user == null){
            throw new \Exception("L'utilisateur n'existe pas !");
        }

        // verifier que l'utilisateur a le ROLE_USER
        $roles = $user->getRoles();
        $roleTrouver = false;
        foreach ($roles as $role) {
            if ($role == "ROLE_USER") {
                $roleTrouver = true;
            }
        }
        if ($roleTrouver == false) {
            throw new \Exception("L'utilisateur n'a pas le role Utilisateur !");
        }

        // Vérification seance existe
        $seance = $this->seanceRepository->findOneBy(['id' => $requete->idSeance]);
        if ($seance == null) {
            throw new \Exception("La seance n'existe pas !");
        }
        // Vérification seance est pas dans le passer
        $dateJour = new \DateTime();
        if ($seance->getDateProjection() < $dateJour) {
            throw new \Exception("La seance est deja passee !");
        }

        // verification nb de place reserver <= nb de place encore dispo (nb place SALLE, nb de place déja reserver)
        $nbPlaceSalle = $seance->getSalle()->getNbPlace();
        // Si null, aucun place encore prise
        $nbPlaceDejaPrise = $this->reservationRepository->findNbPlaceReserverByIdSeance($seance->getId());
        if ($nbPlaceDejaPrise == null) {
            $nbPlaceDejaPrise = 0;
        }
        $nbPlaceDispo = $nbPlaceSalle - $nbPlaceDejaPrise;
        $nbPlaceReserver = $requete->nbPlace;
        if ($nbPlaceReserver > $nbPlaceDispo) {
            throw new \Exception("Le nombre de places reservees est superieur au nombre de places disponibles : il reste $nbPlaceDispo places");
        }



        // Créer le User si il y a pas d'erreur
        $reservation = new Reservation();
        $reservation->setNbPlaceReservation($nbPlaceReserver);
        $reservation->setDateRéservation();
        $reservation->setMontant($seance->getTarifNormal()*$nbPlaceReserver);
        $reservation->setUsers($user);
        $reservation->setSeance($seance);

        // Ajout a la BDD
        $this->entityManager->persist($reservation);
        $this->entityManager->flush();

        return $reservation;

    }


}