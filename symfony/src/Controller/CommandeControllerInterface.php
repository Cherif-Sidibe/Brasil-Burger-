<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

interface CommandeControllerInterface
{
    public function liste(Request $request): Response;

    public function details(Request $request): Response;

    public function changerEtat(Request $request): Response;

    public function assignerLivreur(Request $request): Response;
}
