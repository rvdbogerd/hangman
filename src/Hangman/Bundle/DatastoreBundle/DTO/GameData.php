<?php


namespace Hangman\Bundle\DatastoreBundle\DTO;


/**
 * Class GameData
 *
 * Simple DTO for passing game data to a view or response handler.
 *
 * @package Hangman\Bundle\DatastoreBundle\DTO
 *
 * @author Robbert van den Bogerd <rvdbogerd@ibuildings.nl>
 */
class GameData
{
    /**
     * @var string
     */
    public $word;

    /**
     * @var string
     */
    public $status;

    /**
     * @var integer
     */
    public $tries_left;

    /**
     * @param integer $triesLeft
     * @param string $status one of busy|fail|success
     * @param string $word
     * @param
     */
    public function __construct($triesLeft, $status, $word)
    {
        $this->tries_left = $triesLeft;
        $this->status = $status;
        $this->word = $word;
    }
}
