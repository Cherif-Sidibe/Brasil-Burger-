<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

interface BurgerControllerInterface
{
    public function liste(Request $request): Response;

    public function details(Request $request): Response;

    public function archiver(Request $request): Response;

    public function restaurer(Request $request): Response;
}
