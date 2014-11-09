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
}
