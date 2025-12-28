<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;

interface GestionnaireControllerInterface
{
    public function dashboard(): Response;
}
