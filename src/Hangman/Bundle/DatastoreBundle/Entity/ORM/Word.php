<?php
namespace Hangman\Bundle\DatastoreBundle\Entity\ORM;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository")
 * @ORM\Table(name="word")
 */
class Word
{
    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(name="word", type="string")
     */
    protected $word;

    /**
     * @param string $word
     */
    public function __construct($word)
    {
        $this->word = (string) $word;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->word;
    }

    /**
     * @param array $guessedCharacters
     * @return bool
     */
    public function matchesGuessedCharacters(array $guessedCharacters)
    {
        foreach (str_split($this->word) as $wordCharacter) {
            if (!in_array($wordCharacter, $guessedCharacters)) {
                return false;
            }
        }
        return true;
    }

    /**
     * @param array $characters
     * @return string
     */
    public function withOnlyGuessedCharacters(array $characters)
    {
        $word = '';
        foreach (str_split($this->word) as $wordCharacter) {
            if (!in_array($wordCharacter, $characters)) {
                $word .= '.';
                continue;
            }

            $word .= $wordCharacter;
        }
        return $word;
    }

    /**
     * Checks if the word contains a specific character
     *
     * @param $character
     * @return bool
     */
    public function contains($character)
    {
        return strpos($this->word, $character) !== false;
    }
}
