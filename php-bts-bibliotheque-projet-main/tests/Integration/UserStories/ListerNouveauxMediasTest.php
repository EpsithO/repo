<?php

namespace tests\integration\UserStories;


use App\entity\Media;
use App\entity\StatutMedia;
use App\UserStories\CreerLivre\CreerLivre;
use App\UserStories\CreerLivre\CreerLivreRequete;
use App\Validateurs\Validateur;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ListerNouveauxMediasTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private ValidatorInterface $validator;
    private Validateur $validateurBDD;
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
        $this->validator=Validation::createValidatorBuilder()->getValidator();
        $this->validateurBDD=new Validateur();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
    #[Test]
    public function listerNouveauxMedias_PresentEnBdd_NotEmpty(){
        //Arrange
        $livre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $requete=new CreerLivreRequete("test","4584d","Tom",478);
        $livre->execute($requete);
        $lister=new \App\UserStories\ListerNouveauxMedias\ListerNouveauxMedias($this->entityManager);
        //Act
        $medias=$lister->execute();

        //Assert
        $this->assertNotEmpty($medias);

    }
    #[Test]
    public function listerNouveauxMedias_MediasNouveauNonPresentEnBDD_Empty(){
        //Arrange
        $lister=new \App\UserStories\ListerNouveauxMedias\ListerNouveauxMedias($this->entityManager);
        //Act
        $medias=$lister->execute();
        //Assert
        $this->assertEmpty($medias);

    }
    #[Test]
    public function listerNouveauxMedias_MediaEstNouveau_true(){
        //Arrange
        $livre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $requete=new CreerLivreRequete("test","4584d","Tom",478);
        $livre->execute($requete);
        $lister=new \App\UserStories\ListerNouveauxMedias\ListerNouveauxMedias($this->entityManager);
        //Act
        $media=$lister->execute();
        $statut=$media[0]->getStatut();
        //Assert
        $this->assertEquals("Nouveau",$statut);
    }
    #[Test]
    public function listerNouveauxMedias_MediaEstPasNouveau_True(){
        //Arrange
        $livre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $requete=new CreerLivreRequete("test","4584d","Tom",478);
        $livre->execute($requete);
        $lister=new \App\UserStories\ListerNouveauxMedias\ListerNouveauxMedias($this->entityManager);
        //Act
        $media=$lister->execute();
        $media[0]->setStatut(StatutMedia::DISPONIBLE);
        $statut=$media[0]->getStatut();
        //Assert
        $this->assertNotEquals("Nouveau",$statut);
    }



}