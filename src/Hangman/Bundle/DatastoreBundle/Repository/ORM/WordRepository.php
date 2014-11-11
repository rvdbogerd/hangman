<?php
namespace Hangman\Bundle\DatastoreBundle\Repository\ORM;

use RuntimeException;
use Doctrine\ORM\EntityRepository;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Word;

class WordRepository extends EntityRepository
{
    /**
     * @return Word
     * @throws \RuntimeException
     */
    public function getRandomWord()
    {
        $entityManager = $this->getEntityManager();
        $connection = $entityManager->getConnection();

        $sql = "SELECT w.word
                FROM word AS w
                JOIN (SELECT CEIL(RAND() * (SELECT MAX(id) FROM word)) AS id) AS tmp
                WHERE w.id >= tmp.id
                ORDER BY w.id ASC
                LIMIT 1";

        $stmt = $connection->query($sql);
        $result = $stmt->fetch();

        if(false === $result) {
            throw new RuntimeException('No words available');
        }

        return $this->findOneByWord($result['word']);
    }
}
