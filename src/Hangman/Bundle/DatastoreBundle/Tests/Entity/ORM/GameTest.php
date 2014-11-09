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

        $this->assertSame('awesome', \PHPUnit_Framework_Assert::readAttribute($game, 'word'));
    }

    public function testGuessingShouldChangeNumberOfTriesLeft()
    {
        $game = Game::start(new Word('awesome'));
        $game->guess('a');
        $this->assertSame(10, $game->numberOfTriesLeft());
    }

    public function testGuessingCorrectlyShouldResultInStatusSuccess()
    {
        $game = Game::start(new Word('awesome'))
            ->guess('a')
            ->guess('w')
            ->guess('e')
            ->guess('s')
            ->guess('o')
            ->guess('m');

        $this->assertTrue($game->isFinished());
        $this->assertTrue($game->wordIsGuessed());
    }

    public function testTooManyWrongGuessesShouldResultInStatusFailed()
    {
        $game = Game::start(new Word('awesome'))
            ->guess('c')
            ->guess('v')
            ->guess('b')
            ->guess('l')
            ->guess('p')
            ->guess('q')
            ->guess('x')
            ->guess('d')
            ->guess('f')
            ->guess('g')
            ->guess('h');

        $this->assertTrue($game->isFinished());
        $this->assertFalse($game->wordIsGuessed());
    }

    public function testGuessingCharacterTwiceResultsInException() {
        $this->setExpectedException('Hangman\Bundle\DatastoreBundle\Exception\InvalidCharacterGuessedException');

        $game = Game::start(new Word('awesome'));
        $game
            ->guess('a')
            ->guess('a');
    }
}
