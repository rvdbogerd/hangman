<?php


namespace Hangman\Bundle\ApiBundle\Tests\Controller;

use Hangman\Bundle\ApiBundle\Controller\HangmanGameController;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Word;

/**
 * Class HangmanGameControllerTest
 *
 * @package Hangman\Bundle\ApiBundle\Tests\Controller
 *
 * @author Robbert van den Bogerd <rvdbogerd@ibuildings.nl>
 */
class HangmanGameControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testStartGameShouldReturnJsonResponse()
    {
        $controller = new HangmanGameController($this->getGameServiceMock());
        $response = $controller->startAction();

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertSame(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            '{"word":".......","status":"busy","tries_left":11}',
            $response->getContent()
        );
    }

    public function testGuessShouldReturnJsonResponse()
    {
        $controller = new HangmanGameController($this->getGameServiceMock());
        $response = $controller->guessAction(1, $this->mockGuessRequest());

        $this->assertInstanceOf('Symfony\Component\HttpFoundation\JsonResponse', $response);
        $this->assertSame(200, $response->getStatusCode());

        $this->assertJsonStringEqualsJsonString(
            '{"word":".......","status":"busy","tries_left":11}',
            $response->getContent()
        );
    }

    protected function getGameServiceMock()
    {
        $mock = $this->getMockBuilder('Hangman\Bundle\GameBundle\Service\GameService')
            ->disableOriginalConstructor()
            ->getMock();

        // Always return a game dto when a method is called on GameService
        $game = Game::start(new Word('awesome'));
        $mock->expects($this->any())
            ->method($this->anything())
            ->will($this->returnValue($game->toDto()));

        return $mock;
    }

    protected function mockGuessRequest()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->disableOriginalConstructor()
            ->getMock();

        $request->expects($this->once())
            ->method('get')
            ->will($this->returnValue('a'));

        return $request;
    }
} 
