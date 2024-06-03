<?php

namespace tests\integration\UserStories;



use App\entity\Livre;
use App\Services\GenerateurNumeroAdherent;
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

class CreerLivreTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected ValidatorInterface $validator;
    protected Validateur $validateurBDD;
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
        $this->validator=Validation::createValidatorBuilder()->enableAttributeMapping()->getValidator();
        $this->validateurBDD=new Validateur();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }
    #[Test]
    public function creerLivre_valeursCorrectes_TRUE(){
        //Arrange
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan Lee",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        $creerLivre->execute($requete);
        //Assert
        $repository=$this->entityManager->getRepository(Livre::class);
        $livre=$repository->findOneBy(["isbn"=>"148-DFDZSS"]);
        $this->assertNotNull($livre);
        $this->assertEquals("Spider-man",$requete->titre);
    }
    #[Test]
    public function creerLivre_AuteurVide_Exception(){
        //Arrange
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("L'auteur est obligatoire");
        $creerLivre->execute($requete);

    }
    #[Test]
    public function creerLivre_TitreVide_Exception(){
        //Arrange
        $requete=new CreerLivreRequete("","148-DFDZSS","Stan Lee",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le titre est obligatoire");
        $creerLivre->execute($requete);

    }
    #[Test]
    public function creerLivre_nbPagesVide_Exception(){
        //Arrange
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan Lee",0);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le nombre de pages doit être positif");
        $creerLivre->execute($requete);

    }
    #[Test]
    public function creerLivre_ISBNDejaUtilise_Exception(){
        //Arrange
        $requete=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan LEe",456);
        $creerLivre=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        $requete2=new CreerLivreRequete("Spider-man","148-DFDZSS","Stan LEe",456);
        $creerLivre2=new CreerLivre($this->entityManager,$this->validator,$this->validateurBDD);
        //Act
        $creerLivre->execute($requete);
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Cet isbn est déjà utilisé");
        $creerLivre2->execute($requete2);

    }


}