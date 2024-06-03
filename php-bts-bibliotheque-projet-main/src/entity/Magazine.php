<?php

namespace App\entity;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;

#[Entity]
class Magazine extends Media
{
    #[Column (type: "integer")]
    private int $numero;
    #[Column(type: "date")]
    private \DateTime $datePublication;

    public function __construct()
    {
        parent::__construct();
    }

    public function getNumero(): int
    {
        return $this->numero;
    }

    public function setNumero(int $numero): void
    {
        $this->numero = $numero;
    }

    public function getDatePublication(): \DateTime
    {
        return $this->datePublication;
    }

    public function setDatePublication(\DateTime $datePublication): void
    {

        $this->datePublication = $datePublication;
    }


    function getType(): string
    {
        return strtolower((new \ReflectionClass($this))->getShortName());
    }
}