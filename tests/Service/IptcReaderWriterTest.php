<?php

namespace App\Tests\Service;

use App\Model\IptcData;
use App\Service\IptcHeaderKey;
use App\Service\IptcReader;
use App\Service\IptcWriter;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class IptcReaderWriterTest extends KernelTestCase
{
    public function testWrite()
    {
        self::bootKernel();
        $container = static::getContainer();

        /** @var IptcWriter $writer */
        $writer = $container->get(IptcWriter::class);

        $data = new IptcData();
        $data->setComment("comment")
             ->setAuthor("author")
             ->setCopyright("copyright");

        try {
            $writer->write("web-design-3411373.jpg", $data);
            $this->assertTrue(true);
        } catch(Exception $e) {
            $this->assertTrue(false);
        }
    }

    /**
     * @depends testWrite
     */
    public function testRead()
    {
        self::bootKernel();
        $container = static::getContainer();

        $imageName = "web-design-3411373.jpg";

        /** @var IptcReader $reader */
        $reader = $container->get(IptcReader::class);

        $this->assertEquals("comment", $reader->read($imageName, IptcHeaderKey::COMMENT));
        $this->assertEquals("author", $reader->read($imageName, IptcHeaderKey::AUTHOR));
        $this->assertEquals("copyright", $reader->read($imageName, IptcHeaderKey::COPYRIGHT));
    }
}
