<?php

namespace tests\integration\UserStories;

use App\entity\Adherent;
use App\Services\GenerateurNumeroAdherent;
use App\UserStories\CreerAdherent\CreerAdherent;
use App\UserStories\CreerAdherent\CreerAdherentRequete;
use App\Validateurs\Validateur;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\NotSupported;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreerAdherentTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected GenerateurNumeroAdherent $generateurNumeroAdherent;
    protected ValidatorInterface $validator;
    protected Validateur $validateurBDD;
// Methode exectuée avant chaque test
    protected function setUp() : void
    {
        echo "setup ---------------------------------------------------------";
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
        $this->generateurNumeroAdherent=new GenerateurNumeroAdherent();
        $this->validator=Validation::createValidatorBuilder()->enableAnnotationMapping()->getValidator();
        $this->validateurBDD=new Validateur();

        // Création du schema de la base de données
        $schemaTool = new SchemaTool($this->entityManager);
        $metadata = $this->entityManager->getMetadataFactory()->getAllMetadata();
        $schemaTool->createSchema($metadata);
    }


    #[test]
    public function creerAdherent_ValeursCorrectes_True() {
        // Arrange
        $requete=new CreerAdherentRequete("Arturs","Mednis",'artursmednis@gmail.com');
        $creerAdherent=new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validator,$this->validateurBDD);
        // Act
        $resultat=$creerAdherent->execute($requete);
        // Assert
        $repository=$this->entityManager->getRepository(Adherent::class);
        $adherent=$repository->findOneBy(['mailAdherent'=>"artursmednis@gmail.com"]);
        $this->assertNotNull($adherent);
        $this->assertEquals("Arturs",$requete->prenom);
        $this->assertEquals("Mednis",$requete->nom);
    }
    #[test]
    public function creerAdherent_NomVide_Exception()
    {
        // Arrange
        $requete = new CreerAdherentRequete("Arturs", "", 'artursmednis@gmail.com');
        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validator,$this->validateurBDD);
        // Act

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le nom est obligatoire");
        $resultat = $creerAdherent->execute($requete);
    }
    #[Test]
    public function creerAdherent_PrenomVide_Exception()
    {
        // Arrange
        $requete = new CreerAdherentRequete("", "Mednis", 'artursmednis@gmail.com');
        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validator,$this->validateurBDD);
        // Act

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le prénom est obligatoire");
        $resultat = $creerAdherent->execute($requete);
    }
    #[Test]
    public function creerAdherent_MailVide_Exception()
    {
        // Arrange
        $requete = new CreerAdherentRequete("Arturs", "Mednis", '');
        $creerAdherent = new CreerAdherent($this->entityManager, $this->generateurNumeroAdherent, $this->validator,$this->validateurBDD);
        // Act

        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("Le mail est obligatoire");
        $resultat = $creerAdherent->execute($requete);
    }
    #[test]
    public function creerAdherent_mailDejaUtilise_Exception(){
        //Arrange
        $requete=new CreerAdherentRequete("Arturs","Mednis","artursmednis2003@gmail.com");
        $ceerAdherent=new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validator,$this->validateurBDD);

        $requete2=new CreerAdherentRequete("Arturse","Mednise","artursmednis2003@gmail.com");
        $ceerAdherent2=new CreerAdherent($this->entityManager,$this->generateurNumeroAdherent,$this->validator,$this->validateurBDD);
        //Act
        $ceerAdherent->execute($requete);
        $this->expectException(\Exception::class);
        $ceerAdherent2->execute($requete);

    }


}