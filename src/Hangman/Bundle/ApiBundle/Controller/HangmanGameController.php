<?php

namespace Hangman\Bundle\ApiBundle\Controller;

use Hangman\Bundle\GameBundle\Service\GameService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
     * @param string $character
     * @return JsonResponse
     * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
     */
    public function guessAction($gameId, $character)
    {
        try {
            return new JsonResponse(
                $this->gameService->guess($gameId, $character)
            );
        } catch (\InvalidArgumentException $exception) {
            throw new BadRequestHttpException();
        }
    }
}
