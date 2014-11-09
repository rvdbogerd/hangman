<?php
namespace Hangman\Bundle\DatastoreBundle\Entity\ORM;

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
     * @param $character
     * @throws InvalidCharacterGuessedException
     */
    public function guess($character)
    {
        if(in_array($character, $this->charactersGuessed)) {
            throw new InvalidCharacterGuessedException('Character was already guessed, please try again.');
        }

        $this->charactersGuessed[] = $character;
        $this->triesLeft -= 1;

        $this->updateStatusAfterGuessing();
    }

    /**
     * @return mixed
     */
    public function wordIsGuessed()
    {
        //return $this->word->matchesGuessedCharacters($this->charactersGuessed);
        return true;
    }

    /**
     * @return bool
     */
    public function isFinished()
    {
        return $this->status === self::STATUS_SUCCESS || $this->status === self::STATUS_FAIL;
    }

    /**
     * @return integer
     */
    public function numberOfTriesLeft()
    {
        return $this->triesLeft;
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
     * @return integer
     */
    protected function getId()
    {
        return $this->id;
    }

    /**
     * @throws InvalidArgumentException
     */
    protected function updateStatusAfterGuessing()
    {
        if ($this->wordIsGuessed()) {
            $this->status = self::STATUS_SUCCESS;
        }
        if ($this->numberOfTriesLeft() === 0) {
            $this->status = self::STATUS_FAIL;
        }
    }
}
