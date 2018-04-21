<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @group basketController
 */
class BasketControllerTest extends WebTestCase
{
    public function testBasket()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/basket');

        $this->assertSame(200, $client->getResponse()->getStatusCode());

        $basket = $crawler->filterXpath('//body/nav/div/div[2]/ul[2]/li/a')->text();

        $this->assertRegExp('/\(\d\)/', $basket);
    }
}
