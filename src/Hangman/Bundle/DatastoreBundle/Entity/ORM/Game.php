<?php
namespace Hangman\Bundle\DatastoreBundle\Entity\ORM;

use Hangman\Bundle\DatastoreBundle\DTO\GameData;
use Hangman\Bundle\DatastoreBundle\Exception\GameAlreadyFinishedException;
use Hangman\Bundle\DatastoreBundle\Exception\InvalidCharacterGuessedException;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="game")
 */
class Game
{
    const STATUS_BUSY = 'busy';

    const STATUS_FAIL = 'fail';

    const STATUS_SUCCESS = 'success';

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="tries_left", type="integer")
     */
    private $triesLeft = 11;

    /**
     * @ORM\Column(name="word", type="string")
     */
    private $word;

    /**
     * @ORM\Column(name="status", type="string")
     */
    private $status = self::STATUS_BUSY;

    /**
     * @ORM\Column(name="characters_guessed", type="json_array")
     */
    private $charactersGuessed = array();

    /**
     * @param Word $word
     */
    private function __construct(Word $word)
    {
        $this->word = (string) $word;
    }

    /**
     * @param Word $word
     * @return Game
     */
    public static function start(Word $word)
    {
        return new static($word);
    }

    /**
     * Method for guessing a single character of the word:
     *
     * - adds $character to the 'charactersGuessed' property
     * - will decrease the triesLeft property when word does not contain character
     * - will set the game status to 'fail' when triesLeft = 0
     * - will set the game status to 'success' when all characters are guessed
     *
     * @param $character
     * @throws InvalidCharacterGuessedException
     * @throws GameAlreadyFinishedException
     * @return $this
     */
    public function guess($character)
    {
        if ($this->isFinished()) {
            throw new GameAlreadyFinishedException('Game already finished, please start a new game');
        }

        if (in_array($character, $this->charactersGuessed)) {
            throw new InvalidCharacterGuessedException('Character was already guessed, please try again.');
        }

        if (!preg_match('#^[a-z]{1}$#', $character)) {
            throw new InvalidCharacterGuessedException($character . ' is not a valid character');
        }

        $this->charactersGuessed[] = $character;
        $this->updateStatusAfterGuessing($character);

        return $this;
    }

    /**
     * Meant to expose some properties for use outside of this object context
     *
     * @return GameData
     */
    public function toDto()
    {
        return new GameData(
            $this->triesLeft,
            $this->status,
            $this->getWord()->withOnlyGuessedCharacters($this->charactersGuessed),
            $this->id
        );
    }

    /**
     * @return boolean
     */
    private function isWordGuessed()
    {
        return $this->getWord()->matchesGuessedCharacters($this->charactersGuessed);
    }

    /**
     * @return boolean
     */
    private function isFinished()
    {
        return $this->status === self::STATUS_SUCCESS || $this->status === self::STATUS_FAIL;
    }

    /**
     * @return integer
     */
    private function numberOfTriesLeft()
    {
        return $this->triesLeft;
    }

    /**
     * @return Word
     */
    private function getWord()
    {
        return new Word($this->word);
    }

    /**
     * @param string $character
     */
    private function updateStatusAfterGuessing($character)
    {
        if (!$this->getWord()->contains($character)) {
            $this->triesLeft -= 1;
        }

        if ($this->isWordGuessed()) {
            $this->status = self::STATUS_SUCCESS;
        }
        if ($this->numberOfTriesLeft() === 0) {
            $this->status = self::STATUS_FAIL;
        }
    }
}
