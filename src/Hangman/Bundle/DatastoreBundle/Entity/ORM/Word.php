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
     * @param array $characters
     * @return bool
     */
    public function matchesGuessedCharacters(array $characters)
    {
        foreach (str_split($this->word) as $wordCharacter) {
            if (!in_array($wordCharacter, $characters)) {
                return false;
            }
        }
        return true;
    }
}
