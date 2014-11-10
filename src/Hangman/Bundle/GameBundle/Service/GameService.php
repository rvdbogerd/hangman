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
     * Create a new game with a random word, then persist and return the game.
     *
     * @return Game
     */
    public function startNewGame()
    {
        $game = Game::start($this->wordRepository->getRandomWord());
        $this->entityManager->persist($game);
        $this->entityManager->flush();

        return $game;
    }
}
