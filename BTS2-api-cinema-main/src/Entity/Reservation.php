<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['info_reservation'])]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups(['info_reservation'])]
    private ?int $nbPlaceReservation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Groups(['info_reservation'])]
    private ?\DateTimeInterface $DateRéservation = null;

    #[ORM\Column]
    #[Groups(['info_reservation'])]
    private ?float $montant = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Seance $Seance = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?User $users = null;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getNbPlaceReservation(): ?int
    {
        return $this->nbPlaceReservation;
    }

    /**
     * @param int|null $nbPlaceReservation
     */
    public function setNbPlaceReservation(?int $nbPlaceReservation): void
    {
        $this->nbPlaceReservation = $nbPlaceReservation;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getDateRéservation(): ?\DateTimeInterface
    {
        return $this->DateRéservation;
    }

    /**
     * @param \DateTimeInterface|null $DateRéservation
     */
    public function setDateRéservation(): void
    {
        $this->DateRéservation = new \DateTime();
    }

    /**
     * @return float|null
     */
    public function getMontant(): ?float
    {
        return $this->montant;
    }

    /**
     * @param float|null $montant
     */
    public function setMontant(?float $montant): void
    {
        $this->montant = $montant;
    }

    /**
     * @return Seance|null
     */
    public function getSeance(): ?Seance
    {
        return $this->Seance;
    }

    /**
     * @param Seance|null $Seance
     */
    public function setSeance(?Seance $Seance): void
    {
        $this->Seance = $Seance;
    }

    /**
     * @return User|null
     */
    public function getUsers(): ?User
    {
        return $this->users;
    }

    /**
     * @param User|null $users
     */
    public function setUsers(?User $users): void
    {
        $this->users = $users;
    }


}
