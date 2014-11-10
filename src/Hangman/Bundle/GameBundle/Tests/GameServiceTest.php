<?php

namespace Hangman\Bundle\GameBundle\Tests;

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
    public function testCreateNewGameStoresNewGame()
    {
        $service = new GameService(
            $this->getObjectManagerMock(),
            $this->getWordRepositoryMock()
        );

        $service->startNewGame();
    }

    protected function getObjectManagerMock()
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
        $mock->expects($this->once())
            ->method('getRandomWord')
            ->willReturn(new Word('awesome'));

        return $mock;
    }
} 
