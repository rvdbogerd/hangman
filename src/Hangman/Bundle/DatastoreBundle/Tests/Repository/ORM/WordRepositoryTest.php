<?php
namespace Hangman\Bundle\DatastoreBundle\Tests\Repository\ORM;

use Doctrine\ORM\Mapping\ClassMetadata;
use Hangman\Bundle\DatastoreBundle\Entity\ORM\Word;
use PHPUnit_Framework_TestCase;
use Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository;

class WordRepositoryTest extends PHPUnit_Framework_TestCase
{
    protected $expectedWord = 'tested';
    protected $noWordsInDatastore = false;

    /**
     * @expectedException RuntimeException
     */
    public function testNoWordsInDatastore()
    {
        $this->noWordsInDatastore = true;

        $repository = new WordRepository(
            $this->getEntityManagerMock(),
            $this->getClassMetadataMock()
        );

        $repository->getRandomWord();
    }

    public function testRandomWordReturned()
    {
        $repository = $this->getRepositoryStub();

        $this->assertEquals(
            $this->expectedWord,
            $repository->getRandomWord(),
            'Word does not match the expected result'
        );
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getRepositoryStub()
    {
        $stub = $this->getMockBuilder('Hangman\Bundle\DatastoreBundle\Repository\ORM\WordRepository')
            ->setConstructorArgs([
                    $this->getEntityManagerMock(),
                    $this->getClassMetadataMock()
                ]
            )
            ->setMethods(['findOneByWord'])
            ->getMock();

        $stub->expects($this->once())
            ->method('findOneByWord')
            ->with($this->expectedWord)
            ->will($this->returnValue(
                new Word($this->expectedWord))
            );

        return $stub;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getClassMetadataMock()
    {
        return $this->getMockBuilder('Doctrine\ORM\Mapping\ClassMetadata')
                    ->setMethods(array())
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEntityManagerMock()
    {
        $mock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
                     ->setMethods(array('getConnection'))
                     ->disableOriginalConstructor()
                     ->getMock();

        $mock->expects($this->once())
             ->method('getConnection')
             ->will($this->returnValue($this->getConnectionMock()));

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getConnectionMock()
    {
        $mock = $this->getMockBuilder('Doctrine\DBAL\Connection')
                    ->setMethods(array('query'))
                    ->disableOriginalConstructor()
                    ->getMock();

        $mock->expects($this->once())
             ->method('query')
             ->will($this->returnValue($this->getStatementMock()));

        return $mock;
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getStatementMock()
    {
        $mock = $this->getMockBuilder('Doctrine\DBAL\Driver\Statement')
                     ->setMethods(array())
                     ->disableOriginalConstructor()
                     ->getMock();

        if(false === $this->noWordsInDatastore) {
            $mock->expects($this->once())
                 ->method('fetch')
                 ->will($this->returnValue(array('word' => $this->expectedWord)));
        } else {
            $mock->expects($this->once())
                ->method('fetch')
                ->will($this->returnValue(false));
        }

        return $mock;
    }
}
