<?php

namespace Hangman\Bundle\ApiBundle\Controller;

use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class HangmanGameController
{
    /**
     * @var WordRepository
     */
    private $wordRepository;

    /**
     * @param WordRepository $wordRepository
     */
    public function __construct(WordRepository $wordRepository)
    {
        $this->wordRepository = $wordRepository;
    }
    
    /**
     * @return Response
     */
    public function startAction()
    {
        $game = Game::start($this->wordRepository->getRandomWord());

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
        $game = Game::start($this->wordRepository->getRandomWord());
        // call guess() method

        // handle exceptions

        //return json response
        return new JsonResponse($game->toDto());
    }
}
