<?php

namespace tests\integration\UserStories;


use App\entity\Magazine;
use App\Validateurs\Validateur;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class CreerMagazineTest extends TestCase
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
        $this->validator=Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->validateurBDD=new Validateur();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
#[Test]
    public function creerMagazine_ValeursCorrects_TRUE(){
        //Arrange
        $date=\DateTime::createFromFormat("d/m/Y","01/10/2020");
        $magazineRequete=new \App\UserStories\CreerMagazine\CreerMagazineRequete("Vogue",14567,$date);
        $Creermagazine=new \App\UserStories\CreerMagazine\CreerMagazine($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        $Creermagazine->execute($magazineRequete);
        $repository=$this->entityManager->getRepository(Magazine::class);
        //Assert
        $magazine=$repository->findOneBy(['numero'=>14567]);
        $this->assertNotNull($magazine);
        $this->assertEquals("Vogue",$magazineRequete->titre);
    }
    #[Test]
    public function creerMagazine_TitreVide_Exception(){
        //Arrange
        $date=\DateTime::createFromFormat("d/m/Y","01/10/2020");
        $magazineRequete=new \App\UserStories\CreerMagazine\CreerMagazineRequete("",14567,$date);
        $Creermagazine=new \App\UserStories\CreerMagazine\CreerMagazine($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le titre est obligatoire");
        $Creermagazine->execute($magazineRequete);
    }
    #[Test]
    public function creerMagazine_NumeroDejaUtilise_Exception(){
        //Arrange
        $date=\DateTime::createFromFormat("d/m/Y","01/10/2020");
        $magazineRequete=new \App\UserStories\CreerMagazine\CreerMagazineRequete("Vogue",14567,$date);
        $Creermagazine=new \App\UserStories\CreerMagazine\CreerMagazine($this->entityManager,$this->validator,$this->validateurBDD);
        $magazineRequete2=new \App\UserStories\CreerMagazine\CreerMagazineRequete("Vogue",14567,$date);
        $Creermagazine2=new \App\UserStories\CreerMagazine\CreerMagazine($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        //dd($Creermagazine);
        $Creermagazine->execute($magazineRequete);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Ce numero de magazine est deja utilise");
        $Creermagazine2->execute($magazineRequete2);

    }



}