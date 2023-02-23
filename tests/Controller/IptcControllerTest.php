<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IptcControllerTest extends WebTestCase
{
    /**
     * @dataProvider routesLoader
     */
    public function testRoutes(string $url, string $method): void
    {
        $client = static::createClient();
        $crawler = $client->request($method, $url);

        $this->assertResponseIsSuccessful();
    }

    public function routesLoader()
    {
        yield ['/', 'GET'];
        yield ['/details/blog-2355684_1280.jpg', "GET"];
    }
}
