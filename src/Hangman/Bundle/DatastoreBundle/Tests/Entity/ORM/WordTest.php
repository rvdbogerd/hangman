<?php
namespace Hangman\Bundle\DatastoreBundle\Tests\Entity\ORM;

use Hangman\Bundle\DatastoreBundle\Entity\ORM\Game;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Word;
use PHPUnit_Framework_TestCase;

class WordTest extends PHPUnit_Framework_TestCase
{
    public function testAllCharactersGuessedShouldMatchWord()
    {
        $word = new Word('awesome');
        $this->assertTrue($word->matchesGuessedCharacters(['a', 'w', 'e', 's', 'o', 'm']));
    }

    public function testGuessedCharactersShouldNotMatchWord()
    {
        $word = new Word('awesome');
        $this->assertFalse($word->matchesGuessedCharacters(['a', 'w']));
    }

    public function testUnmatchedCharactersShouldBeHidden()
    {
        $word = new Word('awesome');
        $this->assertSame('a...o..', $word->withOnlyGuessedCharacters(['a', 'o']));
    }

    public function testWordContainsCharacter()
    {
        $word = new Word('awesome');
        $this->assertTrue($word->contains('s'));
        // Just to be sure, test that position 0 does not match false check
        $this->assertTrue($word->contains('a'));
    }

    public function testWordDoesNotContainCharacter()
    {
        $word = new Word('awesome');
        $this->assertFalse($word->contains('x'));
    }
}
