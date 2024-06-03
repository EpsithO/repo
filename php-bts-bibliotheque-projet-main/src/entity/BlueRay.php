<?php

namespace App\entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class BlueRay extends Media
{
    #[Column(type: "string")]
    private string $realisateur;
    #[Column(type: "integer")]
    private int $dureeMin;
    #[Column(type: "integer")]
    private int $anneeSortie;
    #[Column (type: "integer")]
    private int $dateLimite;

    public function __construct()
    {
        parent::__construct();
    }

    public function getRealisateur(): string
    {
        return $this->realisateur;
    }

    public function setRealisateur(string $realisateur): void
    {
        $this->realisateur = $realisateur;
    }

    public function getDureeMin(): int
    {
        return $this->dureeMin;
    }

    public function setDureeMin(int $dureeMin): void
    {
        $this->dureeMin = $dureeMin;
    }

    public function getAnneeSortie(): int
    {
        return $this->anneeSortie;
    }

    public function setAnneeSortie(int $anneeSortie): void
    {
        $this->anneeSortie = $anneeSortie;
    }

    public function getDateLimite(): int
    {
        return $this->dateLimite;
    }

    public function setDateLimite(int $dateLimite): void
    {
        $this->dateLimite = $dateLimite;
    }
    function getType(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }

}