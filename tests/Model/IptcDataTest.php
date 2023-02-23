<?php

namespace App\Tests\Model;

use App\Entity\User;
use App\Model\IptcData;
use App\Service\IptcHeaderKey;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

class IptcDataTest extends KernelTestCase
{
    public function testComment(): void
    {
        $comment = 'Comment';
        $data = new IptcData();
        $data->setComment($comment);
        $this->assertEquals($comment, $data->getComment());
    }

    public function testAuthor(): void
    {
        $author = 'Author';
        $data = new IptcData();
        $data->setAuthor($author);
        $this->assertEquals($author, $data->getAuthor());
    }

    public function testCopyright(): void
    {
        $copyright = 'Copyright';
        $data = new IptcData();
        $data->setCopyright($copyright);
        $this->assertEquals($copyright, $data->getCopyright());
    }

    public function testToArray()
    {
        $comment = 'Comment';
        $author = 'Author';
        $copyright = 'Copyright';

        $data = new IptcData();
        $data->setComment($comment)
             ->setAuthor($author)
             ->setCopyright($copyright);

        $this->assertIsArray($data->toArray());
        $this->assertEquals([
            IptcHeaderKey::COMMENT => $data->getComment(),
            IptcHeaderKey::AUTHOR => $data->getAuthor(),
            IptcHeaderKey::COPYRIGHT => $data->getCopyright()
        ], $data->toArray());
    }

    public function testValidation()
    {
        $comment = str_repeat('c', 2001);
        $author = str_repeat('a', 33);
        $copyright = str_repeat('c', 129);

        $data = new IptcData();
        $data->setComment($comment)
             ->setAuthor($author)
             ->setCopyright($copyright);

        /** @var ConstraintViolationList $errors */
        $errors = $this->getContainer()->get("validator")->validate($data);

        $this->assertEquals(3, count($errors));
        $this->assertEquals("comment", $errors[0]->getPropertyPath());
        $this->assertEquals("author", $errors[1]->getPropertyPath());
        $this->assertEquals("copyright", $errors[2]->getPropertyPath());
    }
}
