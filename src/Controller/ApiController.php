<?php

declare(strict_types=1);

namespace App\Controller;

use App\View\GameView;
use App\View\UserView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class ApiController extends Controller
{
    public function __construct(
        EntityManagerInterface $entityManager
    ){
        parent::__construct($entityManager);
    }

    public function healthCheckAction(Request $request): Response
    {
        return new JsonResponse();
    }
}