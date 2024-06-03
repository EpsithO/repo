<?php

namespace Tests\Unit\EmpruntTest;

use App\entity\Emprunt;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class EmpruntTest extends TestCase
{
     #[test]
    public function estEnCours_DateRetourNonRenseignee_true(){
        $emprunt=new Emprunt();
        $emprunt->setDateEmprunt("17/10/2023");
        $resultat=$emprunt->estEnCours();
        $this->assertTrue($resultat);
    }


    #[test]
    public function estEnCours_DateRetourRenseignee_false(){
        // Arrange
        // Act
        // Assert
        $emprunt=new Emprunt();
        $emprunt->setDateEmprunt("17/10/2023");
        $emprunt->setDateRetour("18/10/2023");
        $resultat=$emprunt->estEnCours();
        $this->assertFalse($resultat);
    }
    #[test]
    public function testEmprunt_RetardPasDeDateDeRetour_true(){
        $emprunt=new Emprunt();
        $emprunt->setDateEmprunt("15/10/2023");
        $emprunt->setDateRetourEstimee("16/10/2023");
        $resultat=$emprunt->estRetard();
        $this->assertTrue($resultat);
    }
    #[test]
    public function testEmprunt_NonRetard_false()
    {
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt("15/10/2023");
        $emprunt->setDateRetourEstimee("17/10/2023");
        $emprunt->setDateRetour("16/10/2023");
        $resultat = $emprunt->estRetard();
        $this->assertFalse($resultat);
    }
    #[test]
    public function testEmprunt_RetardCarDateRetourTropTard_true()
    {
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt("15/10/2023");
        $emprunt->setDateRetourEstimee("17/10/2023");
        $emprunt->setDateRetour("18/10/2023");
        $resultat = $emprunt->estRetard();
        $this->assertTrue($resultat);
    }
    #[test]
    public function testEmprunt_NonRetardCarDateRetourNormal_false()
    {
        $emprunt = new Emprunt();
        $emprunt->setDateEmprunt("15/10/2023");
        $emprunt->setDateRetourEstimee("17/10/2023");
        $emprunt->setDateRetour("16/10/2023");
        $resultat = $emprunt->estRetard();
        $this->assertFalse($resultat);
    }
}