<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ViewController extends Controller
{
    public function game(Request $request): Response
    {
        $game = $request->get('id');

        return $this->render(
            'game.html.twig',
            ['game' => $game]
        );
    }
}