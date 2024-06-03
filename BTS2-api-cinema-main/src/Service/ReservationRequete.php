<?php

namespace App\Service ;
use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;
class ReservationRequete {
    #[Assert\NotBlank(
        message: "L'ID est obligatoire"
    )]
    public string $idSeance ;

    #[Assert\NotBlank(
        message: "Le nombre de place est obligatoire"
    )]
    #[Assert\Positive(
        message: "Le nombre de place doit est positif"
    )]
    public int $nbPlace ;
    public User $user;

    /**
     * @param string $idSeance
     * @param int $nbPlace
     */
    public function __construct(string $idSeance, int $nbPlace)
    {
        $this->idSeance = $idSeance;
        $this->nbPlace = $nbPlace;
    }

}