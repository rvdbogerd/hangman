<?php

namespace Hangman\Bundle\GameBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\Persistence\ObjectRepository;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Hangman\Bundle\DatastoreBundle\Exception\InvalidCharacterGuessedException;
use Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

/**
 * Class GameService can be considered a Façade for the domain model and
 * persistence of the Game logic and flow. It's used for mapping game in-/output
 * from controller to model and back. In between, it persists any changes to the
 * persistence manager.
 *
 * @package Hangman\Bundle\GameBundle\Service
 *
 * @author Robbert van den Bogerd <rvdbogerd@ibuildings.nl>
 */
class GameService
{
    /**
     * @var ObjectManager
     */
    private $persistenceManager;

    /**
     * @var WordRepository
     */
    private $wordRepository;

    /**
     * @var ObjectRepository
     */
    private $gameRepository;

    /**
     * @param ObjectManager $persistenceManager
     * @param WordRepository $wordRepository
     * @param ObjectRepository $gameRepository
     */
    public function __construct(
        ObjectManager $persistenceManager,
        WordRepository $wordRepository,
        ObjectRepository $gameRepository
    ) {
        $this->persistenceManager = $persistenceManager;
        $this->wordRepository = $wordRepository;
        $this->gameRepository = $gameRepository;
    }

    /**
     * @return \Hangman\Bundle\DatastoreBundle\DTO\GameData
     */
    public function startNewGame()
    {
        $game = Game::start($this->wordRepository->getRandomWord());
        $this->saveGame($game);

        return $game->toDto();
    }

    /**
     * @param integer $gameId
     * @param string $character
     * @return \Hangman\Bundle\DatastoreBundle\DTO\GameData
     */
    public function guess($gameId, $character)
    {
        $game = $this->findGame((int) $gameId);
        $game->guess($character);
        $this->saveGame($game);

        return $game->toDto();
    }

    /**
     * @param $gameId
     * @return Game
     * @throws \Symfony\Component\Routing\Exception\ResourceNotFoundException
     * @throws \InvalidArgumentException
     */
    private function findGame($gameId)
    {
        if (!is_integer($gameId)) {
            throw new \InvalidArgumentException($gameId . ' is not an integer');
        }

        $game = $this->gameRepository->find($gameId);
        if (!$game instanceof Game) {
            throw new ResourceNotFoundException('Game with id ' . $gameId . ' does not exist');
        }

        return $game;
    }

    /**
     * @param Game $game
     */
    private function saveGame(Game $game)
    {
        $this->persistenceManager->persist($game);
        $this->persistenceManager->flush();
    }
}
