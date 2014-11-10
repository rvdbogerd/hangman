<?php

namespace Hangman\Bundle\ApiBundle\Controller;

use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HangmanGameController extends Controller
{
    /**
     * @return Response
     */
    public function startAction()
    {
        $game = Game::start(
            $this->container->get('hangman_datastore.word_repository')
                ->getRandomWord()
        );

        // Save game

        return new JsonResponse($game->toDto());
    }

    /**
     * @param integer $gameId
     * @return Response
     */
    public function guessAction($gameId)
    {
        // find game by id

        // call guess() method

        // handle exceptions

        //return json response
        return new JsonResponse($game->toDto());
    }
}
