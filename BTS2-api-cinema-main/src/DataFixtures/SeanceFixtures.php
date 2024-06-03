<?php

namespace App\DataFixtures;


use App\Entity\Seance;
use App\Repository\FilmRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
;

class SeanceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create();

        for ($i=0;$i<=20;$i++) {
           $seance = new Seance();
           $seance->setDateProjection($faker->dateTimeBetween("now","+15 days"));
           $tarif = random_int(500,1000)/100;
           $seance->setTarifNormal($tarif);
           $seance->setTarifReduit($tarif * 0.7);

           $seance->setFilm($this->getReference("Film".$i));
            $seance->setSalle($this->getReference("Salle".$i));
           $manager->persist($seance);
        }
        $manager->flush();
    }

    public function getDependencies() : array
    {
        return [
            SalleFixtures::class,
            FilmFixtures::class,
        ];
    }
}
