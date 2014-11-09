<?php

namespace Hangman\Bundle\ApiBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HangmanGameController extends Controller
{
    /**
     *
     *
     * @return Response
     */
    public function startAction()
    {
        return new JsonResponse([
                'word' => '..df..',
                'tries_left' => 6,
                'status' => 'busy|fail|success'
            ]
        );
    }
}
