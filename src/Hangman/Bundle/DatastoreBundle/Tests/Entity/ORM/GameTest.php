<?php
namespace Hangman\Bundle\DatastoreBundle\Tests\Entity\ORM;

use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Word;
use PHPUnit_Framework_TestCase;

class GameTest extends PHPUnit_Framework_TestCase
{
    public function testStartShouldCreateANewGameWithGivenWord()
    {
        $game = Game::start(new Word('awesome'));

        $this->assertSame('awesome', $game->getWord());
    }

    public function testGuessingShouldChangeNumberOfTriesLeft()
    {
        $game = Game::start(new Word('awesome'));
        $game->guess('a');
        $this->assertSame(10, $game->numberOfTriesLeft());
    }

    public function testGuessingCorrectlyShouldResultInStatusSuccess()
    {
        $game = Game::start(new Word('awesome'));
        $game->guess('a');
        $game->guess('w');
        $game->guess('e');
        $game->guess('s');
        $game->guess('o');
        $game->guess('m');

        $this->assertTrue($game->isFinished());
        $this->assertTrue($game->wordIsGuessed());
    }
}
