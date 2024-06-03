<?php

namespace App\entity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;

#[Entity]
class Adherent
{
    #[Id]
    #[Column(type: Types::INTEGER)]
    #[GeneratedValue]
    private int $id;
    #[Column(length: 14)]
    private string $numeroAdherent;
    #[Column(length: 50)]
    private string $prenomAdherent;
    #[Column(length: 60)]
    private string $nomAdherent;
    #[Column(length: 60)]
    private string $mailAdherent;
    #[Column(type: Types::DATE_MUTABLE,nullable: true)]
    private ?\DateTime $dateAdhesion=null;

    public function __construct()
    {
    }

    public function getId():int
    {
        return $this->id;
    }
    public function getNumeroAdherent(): string
    {
        return $this->numeroAdherent;
    }

    public function setNumeroAdherent(string $numeroAdherent): void
    {
        $this->numeroAdherent = $numeroAdherent;
    }

    public function getPrenomAdherent(): string
    {
        return $this->prenomAdherent;
    }

    public function setPrenomAdherent(string $prenomAdherent): void
    {
        $this->prenomAdherent = $prenomAdherent;
    }

    public function getNomAdherent(): string
    {
        return $this->nomAdherent;
    }

    public function setNomAdherent(string $nomAdherent): void
    {
        $this->nomAdherent = $nomAdherent;
    }

    public function getMailAdherent(): string
    {
        return $this->mailAdherent;
    }

    public function setMailAdherent(string $mailAdherent): void
    {
        $this->mailAdherent = $mailAdherent;
    }

    public function getDateAdhesion(): string
    {
        return $this->dateAdhesion->format("d/m/Y");
    }

    public function setDateAdhesion(string $dateAdhesion): void
    {
        $this->dateAdhesion = \DateTime::createFromFormat("d/m/Y",$dateAdhesion);
    }

}