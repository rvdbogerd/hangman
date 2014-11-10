<?php


namespace Hangman\Bundle\GameBundle\Service;

use Doctrine\Common\Persistence\ObjectManager;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository;

/**
 * Class GameService
 * @package Hangman\Bundle\GameBundle\Service
 *
 * @author Robbert van den Bogerd <rvdbogerd@ibuildings.nl>
 */
class GameService
{
    /**
     * @var \Doctrine\Common\Persistence\ObjectManager
     */
    private $entityManager;

    /**
     * @var \Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository
     */
    private $wordRepository;

    /**
     * @param ObjectManager $entityManager
     * @param WordRepository $wordRepository
     */
    public function __construct(
        ObjectManager $entityManager,
        WordRepository $wordRepository
    ) {
        $this->entityManager = $entityManager;
        $this->wordRepository = $wordRepository;
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
     * @param $gameId
     * @param $character
     * @return \Hangman\Bundle\DatastoreBundle\DTO\GameData
     * @throws \InvalidArgumentException
     */
    public function guess($gameId, $character)
    {
        if (!is_string($character) || strlen($character) <> 1) {
            throw new \InvalidArgumentException($character . ' is not a valid character');
        }

        $game = $this->findGame($gameId);
        $game->guess($character);

        return $game->toDto();
    }

    /**
     * @param integer $gameId
     * @return Game
     * @throws \InvalidArgumentException
     */
    private function findGame($gameId)
    {
        if (!is_integer($gameId)) {
            throw new \InvalidArgumentException($gameId . ' is not an integer');
        }

        $game = $this->entityManager->getRepository('Hangman\Bundle\DatastoreBundle\Entity\ORM\Game')
            ->find($gameId);
        if (!$game instanceof Game) {
            throw new \InvalidArgumentException('Game with id ' . $gameId . ' does not exist');
        }

        return $game;
    }

    /**
     * @param Game $game
     */
    private function saveGame(Game $game)
    {
        $this->entityManager->persist($game);
        $this->entityManager->flush();
    }
}
