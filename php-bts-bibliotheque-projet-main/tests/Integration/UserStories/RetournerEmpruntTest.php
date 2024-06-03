<?php

namespace tests\Integration\UserStories;

use App\entity\Adherent;
use App\entity\Emprunt;
use App\entity\Livre;
use App\entity\Media;
use App\entity\StatutMedia;
use App\Services\GenerateurNumeroEmprunt;
use App\Validateurs\Validateur;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class RetournerEmpruntTest extends TestCase
{
    private Validateur $validateurBDD;
    private EntityManagerInterface $entityManager;

    /**
     * @param Validateur $validateur
     * @param EntityManagerInterface $entityManager
     */

    protected function setUp() : void
    {

        // Configuration de Doctrine pour les tests
        $config = ORMSetup::createAttributeMetadataConfiguration(
            [__DIR__.'/../../../src/'],
            true
        );

        // Configuration de la connexion à la base de données
        // Utilisation d'une base de données SQLite en mémoire
        $connection = DriverManager::getConnection([
            'driver' => 'pdo_sqlite',
            'path' => ':memory:'
        ], $config);

        // Création de l'entity manager
        $this->entityManager = new EntityManager($connection, $config);
        $this->validateurBDD=new Validateur();


        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
#[Test]
    public function restituerEmprunt_Succes_NotEmpty_EqualsToDisponible()
    {
        //Arrange
        $media=new Livre();
        $media->setTitre('Spider');
        $media->setAuteur('Stan');
        $media->setStatut(StatutMedia::EMPRUNTE);
        $media->setDureeEmprunt(21);
        $media->setIsbn('4444');
        $media->setNbPages(44);
        $media->setDateCreation(new \DateTime());


        $adherent=new Adherent();
        $adherent->setMailAdherent('artursmednis@gmail.com');
        $adherent->setNomAdherent('MEdnis');
        $adherent->setPrenomAdherent('Art');
        $adherent->setNumeroAdherent('AD-544848');
        $adherent->setDateAdhesion('01/01/2023');

        $date=new \DateTime();
        $emprunt=new Emprunt();
        $emprunt->setNumeroEmprunt('EM-444444');
        $emprunt->setMediaEmprunte($media);
        $emprunt->setDateEmprunt($date);
        $emprunt->setDateRetourEstimee($date->modify('+21 days'));
        $emprunt->setAdherent($adherent);
        $this->entityManager->persist($emprunt);
        $this->entityManager->persist($media);
        $this->entityManager->persist($adherent);
        $this->entityManager->flush();

        $repoEmprunt=$this->entityManager->getRepository(Emprunt::class);
        $retournerEmprunt=new \App\UserStories\RetournerEmprunt\RetournerEmprunt($this->entityManager,$this->validateurBDD);
        $retournerEmprunt->execute("EM-444444");

        $repoMedia=$this->entityManager->getRepository(Livre::class);
        $mediaRestituee=$repoMedia->findOneBy(['isbn'=>'4444']);
        $empruntApresRetour=$repoEmprunt->findOneBy(['numeroEmprunt'=>'EM-444444']);
        $this->assertNotEmpty($empruntApresRetour);
        $this->assertNotEmpty($empruntApresRetour->getDateRetour());
        $this->assertEquals(StatutMedia::DISPONIBLE,$mediaRestituee->getStatut());

    }

}