<?php

namespace Hangman\Bundle\GameBundle\Tests;

use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Word;
use Hangman\Bundle\GameBundle\Service\GameService;

/**
 * Class GameServiceTest
 * @package Hangman\Bundle\GameBundle\Tests
 *
 * @author Robbert van den Bogerd <rvdbogerd@ibuildings.nl>
 */
class GameServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var GameService
     */
    private $gameService;
    private $gameRepositoryMock;
    private $wordRepositoryMock;
    private $persistenceManagerMock;

    public function setUp()
    {
        $this->gameRepositoryMock = $this->getGameRepositoryMock();
        $this->wordRepositoryMock = $this->getWordRepositoryMock();
        $this->persistenceManagerMock = $this->getPersistenceManagerMock();

        $this->gameService = new GameService(
            $this->persistenceManagerMock,
            $this->wordRepositoryMock,
            $this->gameRepositoryMock
        );
    }

    public function testCreateNewGameShouldStoreNewGame()
    {
        $this->expectPersistCalls();

        $this->wordRepositoryMock
            ->expects($this->once())
            ->method('getRandomWord')
            ->willReturn(new Word('awesome'));

        $this->gameService->startNewGame();
    }

    public function testGuessingShouldUpdateGame()
    {
        $this->expectPersistCalls();

        $gameData = $this->gameService->guess(1, 'a');
        $this->assertInstanceOf('Hangman\Bundle\DatastoreBundle\DTO\GameData', $gameData);
    }

    protected function getPersistenceManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->getMock();

        return $mock;
    }

    protected function getWordRepositoryMock()
    {
        $mock = $this->getMockBuilder('Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository')
            ->disableOriginalConstructor()
            ->getMock();

        return $mock;
    }

    protected function getGameRepositoryMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityRepository')
            ->disableOriginalConstructor()
            ->getMock();

        $mock
            ->expects($this->any())
            ->method('find')
            ->willReturn(Game::start(new Word('awesome')));

        return $mock;
    }

    protected function expectPersistCalls()
    {
        $this->persistenceManagerMock
            ->expects($this->once())
            ->method('persist');
        $this->persistenceManagerMock
            ->expects($this->once())
            ->method('flush');
    }
} 
