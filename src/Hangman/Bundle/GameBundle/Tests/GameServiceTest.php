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
    public function testCreateNewGameShouldStoreNewGame()
    {
        $service = new GameService(
            $this->getObjectManagerMock(),
            $this->getWordRepositoryMock()
        );

        $service->startNewGame();
    }

    public function testGuessingShouldUpdateGame()
    {
        $service = new GameService(
            $this->getObjectManagerMock(),
            $this->getWordRepositoryMock()
        );
        $gameData = $service->guess(1, 'a');
        $this->assertInstanceOf('Hangman\Bundle\DatastoreBundle\DTO\GameData', $gameData);
        $this->assertAttributeSame(10, 'tries_left', $gameData);
    }

    public function testGuessingWithInvalidCharacterThrowsException()
    {
        $service = new GameService(
            $this->getObjectManagerMock(),
            $this->getWordRepositoryMock()
        );
        $service->guess(1, 'asdfa');
    }

    protected function getObjectManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\Common\Persistence\ObjectManager')
            ->getMock();

        $mock->expects($this->once())
            ->method('persist');
        $mock->expects($this->once())
            ->method('flush');

        return $mock;
    }

    protected function getWordRepositoryMock()
    {
        $mock = $this->getMockBuilder('Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $mock->expects($this->once())
            ->method('getRandomWord')
            ->willReturn(new Word('awesome'));

        return $mock;
    }
} 
