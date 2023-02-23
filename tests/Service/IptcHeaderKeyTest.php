<?php

namespace App\Tests\Service;

use App\Service\IptcHeaderKey;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class IptcHeaderKeyTest extends KernelTestCase
{
    public function testHeaderKeyExist(): void
    {
        $iptcHeaderKey = new \ReflectionClass(IptcHeaderKey::class);
        $this->assertTrue($iptcHeaderKey->hasConstant("COMMENT"));
        $this->assertTrue($iptcHeaderKey->hasConstant("AUTHOR"));
        $this->assertTrue($iptcHeaderKey->hasConstant("COPYRIGHT"));
    }

    public function testHeaderKeyValue(): void
    {
        $iptcHeaderKey = new \ReflectionClass(IptcHeaderKey::class);
        $this->assertEquals("2#120", $iptcHeaderKey->getConstant("COMMENT"));
        $this->assertEquals("2#122", $iptcHeaderKey->getConstant("AUTHOR"));
        $this->assertEquals("2#116", $iptcHeaderKey->getConstant("COPYRIGHT"));
    }
}
