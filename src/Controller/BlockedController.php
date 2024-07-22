<?php
// src/Controller/BlockedController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlockedController extends AbstractController
{
    #[Route('/blocked', name: 'app_blocked')]
    public function blocked(): Response
    {
        return $this->render('security/blocked.html.twig');
    }
}
