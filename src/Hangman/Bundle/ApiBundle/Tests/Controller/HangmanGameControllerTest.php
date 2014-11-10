<?php


namespace Hangman\Bundle\ApiBundle\Tests\Controller;

use Hangman\Bundle\ApiBundle\Controller\HangmanGameController;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Word;
use Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository;

/**
 * Class HangmanGameControllerTest
 * @package Hangman\Bundle\ApiBundle\Tests\Controller
 *
 * @author Robbert van den Bogerd <rvdbogerd@ibuildings.nl>
 */
class HangmanGameControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testStartGameShouldReturnJsonResponse()
    {
        $controller = new HangmanGameController($this->getWordRepositoryMock());
        $response = $controller->startAction();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertJsonStringEqualsJsonString(
            '{"word":".......","status":"busy","tries_left":11}',
            $response->getContent()
        );
    }

    public function testGuessShouldReturnJsonResponse()
    {
        $controller = new HangmanGameController($this->getWordRepositoryMock());
        $response = $controller->guessAction(1);

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertJsonStringEqualsJsonString(
            '{"word":".......","status":"busy","tries_left":10}',
            $response->getContent()
        );
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
