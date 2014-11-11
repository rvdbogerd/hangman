<?php
namespace Hangman\Bundle\DatastoreBundle\Entity\ORM;

use Hangman\Bundle\DatastoreBundle\DTO\GameData;
use Hangman\Bundle\DatastoreBundle\Exception\GameAlreadyFinishedException;
use Hangman\Bundle\DatastoreBundle\Exception\InvalidCharacterGuessedException;
use InvalidArgumentException;
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
    protected $id;

    /**
     * @ORM\Column(name="tries_left", type="integer")
     */
    protected $triesLeft = 11;

    /**
     * @ORM\Column(name="word", type="string")
     */
    protected $word;

    /**
     * @ORM\Column(name="status", type="string")
     */
    protected $status = self::STATUS_BUSY;

    /**
     * @ORM\Column(name="characters_guessed", type="json_array")
     */
    protected $charactersGuessed = array();

    /**
     * @param Word $word
     */
    protected function __construct(Word $word)
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
     * @return boolean
     */
    protected function isWordGuessed()
    {
        return $this->getWord()->matchesGuessedCharacters($this->charactersGuessed);
    }

    /**
     * @return boolean
     */
    protected function isFinished()
    {
        return $this->status === self::STATUS_SUCCESS || $this->status === self::STATUS_FAIL;
    }

    /**
     * @return integer
     */
    protected function numberOfTriesLeft()
    {
        return $this->triesLeft;
    }

    /**
     * @return integer
     */
    protected function getId()
    {
        return $this->id;
    }

    /**
     * @return Word
     */
    protected function getWord()
    {
        return new Word($this->word);
    }

    /**
     * @param string $character
     */
    protected function updateStatusAfterGuessing($character)
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

    /**
     * @return GameData
     */
    public function toDto()
    {
        return new GameData(
            $this->triesLeft,
            $this->status,
            $this->getWord()->withOnlyGuessedCharacters($this->charactersGuessed)
        );
    }
}
