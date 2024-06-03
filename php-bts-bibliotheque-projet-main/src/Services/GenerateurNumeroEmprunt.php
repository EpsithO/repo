<?php

namespace App\Services;

class GenerateurNumeroEmprunt
{
    public function generer():string
    {
        $numero = 'EM-';
        for ($i = 0; $i < 6; $i++) {
            $numero = $numero . random_int(0, 9);
        }
        return $numero;
    }


}