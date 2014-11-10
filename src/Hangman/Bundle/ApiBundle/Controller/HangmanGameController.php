<?php

namespace Hangman\Bundle\ApiBundle\Controller;

use Hangman\Bundle\GameBundle\Service\GameService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HangmanGameController
{
    /**
     * @var GameService
     */
    private $gameService;

    /**
     * @param GameService $gameService
     */
    public function __construct(GameService $gameService)
    {
        $this->gameService = $gameService;
    }
    
    /**
     * @return Response
     */
    public function startAction()
    {
        $game = $this->gameService->startNewGame();

        return new JsonResponse($game->toDto());
    }

    /**
     * @param integer $gameId
     * @return Response
     */
    public function guessAction($gameId)
    {
        // find game by id
        $game = $this->gameService->startNewGame();
        // call guess() method

        // handle exceptions

        //return json response
        return new JsonResponse($game->toDto());
    }
}
