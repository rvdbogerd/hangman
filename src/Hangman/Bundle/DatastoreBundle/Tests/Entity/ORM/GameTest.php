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
        $game->guess('x');
        $this->assertSame(10, $game->toDto()->tries_left);
    }

    public function testGuessingShouldNotChangeNumberOfTriesLeft()
    {
        $game = Game::start(new Word('awesome'));
        $game->guess('a');
        $this->assertSame(11, $game->toDto()->tries_left);
    }

    public function testGuessingShouldExposeMatchedCharacters()
    {
        $game = Game::start(new Word('awesome'));
        $game->guess('e');
        $this->assertSame('..e...e', $game->toDto()->word);
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

        $gameData = $game->toDto();
        $this->assertSame(Game::STATUS_SUCCESS, $gameData->status);
        $this->assertSame(11, $gameData->tries_left);
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

        $gameData = $game->toDto();
        $this->assertSame(Game::STATUS_FAIL, $gameData->status);
        $this->assertSame(0, $gameData->tries_left);
    }

    public function testGuessingCharacterTwiceResultsInException() {
        $this->setExpectedException('Hangman\Bundle\DatastoreBundle\Exception\InvalidCharacterGuessedException');

        $game = Game::start(new Word('awesome'));
        $game
            ->guess('a')
            ->guess('a');
    }

    public function testGuessingWithInvalidCharacterThrowsException()
    {
        $this->setExpectedException('Hangman\Bundle\DatastoreBundle\Exception\InvalidCharacterGuessedException');
        Game::start(new Word('awesome'))->guess('asdfa');
    }
}
